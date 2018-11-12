<?php

namespace App;

use App\Notifications\PaymentRejectedNotification;
use App\Notifications\PurchaseCompleteNotification;
use App\Transaction;
use App\User;
use App\Account;
use App\Ewallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Purchase extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['user_name'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function accounts()
    {
        return $this->belongsToMany('App\Account', 'package_purchase', 'purchase_id', 'account_id')->withPivot('amount', 'total_price', 'account_id')->withTimestamps();
    }

    public function packages()
    {
    	return $this->belongsToMany('App\Package')->withPivot('amount', 'total_price', 'total_price_std', 'account_id')->withTimestamps();
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function verify()
    {
    	$this->update(['status' => 'complete', 'is_verified' => true]);
        if(!$this->is_account){
            $account = Account::where('id', $this->account_id)->first();
            $account->update([ 'machine_count' => $account->machine_count + $this->packages->sum(function($package){ return $package->pivot->amount * $package->machine_count; }) ]);
            $this->pay_and_roll_commission_upwards($account);
            foreach(Account::ancestorsAndSelf($account) as $account)
            {
                $account->adjust_level($this->is_account);
            }
        }
        else{
            foreach($this->accounts as $account){
                $account->update([
                    'is_verified' => true,
                    'machine_count' => $account->machine_count + $this->packages()->where('account_id', $account->id)->first()->machine_count
                ]);
                $this->pay_and_roll_commission_for_buying_account($account, $account, true);  
            }
            foreach(Account::ancestorsAndSelf($this->accounts()->orderBy('accounts.account_level', 'desc')->first()) as $account)
            {
                $account->adjust_level($this->is_account);
            }
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.receipt', ['purchase' => $this, 'user' => $this->user]);
        $pdf->save('storage/receipts/' . $this->id . '.pdf');

        // Notify user to download receipt
        $this->user->notify(new PurchaseCompleteNotification($this));

        return $this;
    }

    public function pay_and_roll_commission_for_buying_account($account_to_find_package, $account, $first_commission, $paid_percentage = 0.0, $count = 0)
    {
        $purchasedPackage = $this->packages()->where('account_id', $account_to_find_package->id)->first();
        $actual_comm_percentage = 0.9;
        $e_wallet_comm_percentage = 0.1;

        if($first_commission){
            $percentage = $account->commision_percentage > $paid_percentage ? $account->commision_percentage - $paid_percentage : 0.0;

            $transaction = $this->createTransactionQuery($account->user, Transaction::ONE_TIME_COMMISSION(), 'purchase_account', ($purchasedPackage->pivot->total_price * $percentage) * $actual_comm_percentage, $this->created_at, $percentage, $account);

            $ewallet_amount = ($purchasedPackage->pivot->total_price * $percentage) * $e_wallet_comm_percentage;

            $wallet_transaction = $this->createTransactionQuery($account->user, Transaction::E_WALLET_COMMISSION(), 'purchase_account', $ewallet_amount, $this->created_at, $percentage, $account);

            $this->createEwalletQuery($account->user->id, $ewallet_amount);

            $paid_percentage +=  $percentage;
            $this->pay_and_roll_commission_for_buying_account($account_to_find_package, $account, false, $paid_percentage);
        } else {
            if(!is_null($account->parent_id)) {
                $parent = $account->parent;
                $percentage = $parent->commision_percentage > $paid_percentage ? $parent->commision_percentage - $paid_percentage : 0.0;

                if($count < 3) {
                    $nett_percentage = 0.55;
                    $leadership_bonus = ($purchasedPackage->pivot->total_price * $nett_percentage) * $parent->leadership_bonus;

                    $this->createTransactionQuery($parent->user, Transaction::LEADERSHIP_BONUS(), 'purchase_account', $leadership_bonus * 0.9, $this->created_at, $parent->leadership_bonus, $parent);

                    $this->createTransactionQuery($parent->user, Transaction::E_WALLET_LEADERSHIP_BONUS(), 'purchase_account', $leadership_bonus * 0.1, $this->created_at, $parent->leadership_bonus, $parent);

                    $this->createEwalletQuery($parent->user->id, $leadership_bonus * 0.1);
                }

                if($percentage > 0) {
                    $this->createTransactionQuery($parent->user, Transaction::ONE_TIME_COMMISSION(), 'purchase_account', ($purchasedPackage->pivot->total_price * $percentage) * $actual_comm_percentage, $this->created_at, $percentage, $parent);
                    
                    $ewallet_amount = ($purchasedPackage->pivot->total_price * $percentage) * $e_wallet_comm_percentage;

                    $this->createTransactionQuery($parent->user, Transaction::E_WALLET_COMMISSION(), 'purchase_account', ($purchasedPackage->pivot->total_price * $percentage) * $e_wallet_comm_percentage, $this->created_at, $percentage, $parent);

                    $this->createEwalletQuery($parent->user->id, $ewallet_amount);

                    $paid_percentage +=  $percentage;
                }
                $this->pay_and_roll_commission_for_buying_account($account_to_find_package, $parent, false, $paid_percentage, ++$count);
            }
        }
    }

    public function createEwalletQuery($user_id, $amount)
    {
        $current_wallet_amount = 0;
        $ewallet = Ewallet::where('user_id', $user_id)->first();

        if($ewallet){
            $current_wallet_amount = $ewallet->amount;
        }

        $amount = $current_wallet_amount + $amount;

        Ewallet::updateOrCreate(['user_id' => $user_id],
                                ['amount' => $amount]
                                );

    }

    public function createTransactionQuery($user, $type, $description, $amount, $date, $percentage, $account)
    {
        $transaction = Transaction::create($user, 
                                            $type, 
                                            $description, 
                                            $amount, 
                                            0, 
                                            0, 
                                            $date,
                                            $percentage);

        $transaction->update(['purchase_id' => $this->id, 'target_id' => $account->id]);
    }

    public function pay_and_roll_commission_upwards($account, $paid_percentage = 0.0, $count = 0)
    {   
        if(!is_null($account->parent_id)) {
            $parent = $account->parent;
            $percentage = $parent->commision_percentage > $paid_percentage ? $parent->commision_percentage - $paid_percentage : 0.0;
            
            if($count < 3) {
                $nett_percentage = 0.55;
                $leadership_bonus = $this->total_price * $nett_percentage * $parent->leadership_bonus;
                $this->createTransactionQuery($parent->user, Transaction::LEADERSHIP_BONUS(), Transaction::DESCRIPTION_MACHINE_PURCHASE(), $leadership_bonus * 0.9, $this->created_at, $parent->leadership_bonus, $parent);

                $this->createTransactionQuery($parent->user, Transaction::E_WALLET_LEADERSHIP_BONUS(), Transaction::DESCRIPTION_MACHINE_PURCHASE(), $leadership_bonus * 0.1, $this->created_at, $parent->leadership_bonus, $parent);

                $this->createEwalletQuery($parent->user->id, $leadership_bonus * 0.1);
            }

            if($percentage > 0)
            {
                $actual_comm_percentage = 0.9;
                $e_wallet_comm_percentage = 0.1;

                $this->createTransactionQuery($parent->user, Transaction::ONE_TIME_COMMISSION(), Transaction::DESCRIPTION_MACHINE_PURCHASE(), ($this->total_price * $percentage) * $actual_comm_percentage, $this->created_at, $percentage, $parent);

                $ewallet_amount = ($this->total_price * $percentage) * $e_wallet_comm_percentage;
                $this->createTransactionQuery($parent->user, Transaction::E_WALLET_COMMISSION(), Transaction::DESCRIPTION_MACHINE_PURCHASE(), $ewallet_amount, $this->created_at, $percentage, $parent);

                $this->createEwalletQuery($parent->user->id, $ewallet_amount);

                $paid_percentage +=  $percentage;
            }

            $this->pay_and_roll_commission_upwards($parent, $paid_percentage, ++$count);
        }
    }

    public function reject($note)
    {
        $this->update(['status' => 'rejected']);

        // Notify user about this
        $locale = $this->user->country_id == 48 ? 'zh' : 'en';
        $this->user->notify(new PaymentRejectedNotification($this, $locale, $this->user));
    }
}

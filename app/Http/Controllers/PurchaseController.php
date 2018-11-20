<?php

namespace App\Http\Controllers;

use App\Package;
use App\Account;
use App\Purchase;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function validatePurchase()
    {
        $messages = [
            'account_id.required' => 'validation.required',
        ];

        request()->validate([
            'account_id' => 'required',
        ], $messages);
    }

    public function index(User $user)
    {
        return Controller::VueTableListResult($user->purchases()->with('packages', 'payment', 'accounts')
                                                    ->select('purchases.id as id',
                                                            'payment_id',
                                                            'purchases.created_at as created_at',
                                                            'purchases.total_price as total_price',
                                                            'purchases.total_price_std as total_price_std',
                                                            'purchases.is_std as is_std',
                                                            'purchases.status as status',
                                                            'purchases.account_level as account_level',
                                                            'purchases.account_id as account_id',
                                                            'purchases.referral_code as referral_code',
                                                            'purchases.is_account as is_account',
                                                            'user_id',
                                                            'users.name as user_name')
                                                    ->leftJoin('users', 'users.id', '=', 'user_id')
                                                );
    }

    public function store()
    {
        $this->validatePurchase();
    	$packages = json_decode(request()->packages);
        $processed_packages = [];
    	foreach ($packages as $key => $package) {
            if($package->amount > 0) {
                // $db_package = Package::find($package->id);
                // $price = $db_package->price_promotion > 0 ? $db_package->price_promotion : $db_package->price;
                // $price_std = $db_package->price_std_promotion > 0 ? $db_package->price_std_promotion : $db_package->price_std;
                $processed_packages[$package->id]['total_price'] = $package->price * $package->amount;
                $processed_packages[$package->id]['total_price_std'] = $package->price_std * $package->amount;
                $processed_packages[$package->id]['amount'] = $package->amount;
            }
    	}

    	$packages = collect($processed_packages);

    	$total_price = $packages->sum(function($package){
    		return $package['total_price'];
    	});

        $user = User::find(request()->user_id);

        $total_price_std = $packages->sum(function($package){
            return $package['total_price_std'];
        });

    	$purchase = Purchase::create([
    		'user_id' => request()->user_id,
    		'total_price' => $total_price,
            'total_price_std' => $total_price_std,
            'account_id' => request()->account_id,
            'is_std' => $user->is_std,
            'created_at' => request()->purchase_date
    	]);

    	$purchase->packages()->sync($packages);

    	return json_encode(['message' => 'purchase.success', 'purchase' => Purchase::with('packages')->find($purchase->id)]);
    }

    public function accountStore()
    {
        $user = User::find(request()->user_id);

        $purchase = Purchase::create([
            'user_id' => request()->user_id,
            'total_price' => request()->total_price,
            'total_price_std' => 0,
            'is_std' => 0,
            'created_at' => request()->purchase_date,
            'account_level' => request()->account_level,
            'referral_code' => request()->referral_code,
            'is_account' => true,
        ]);

        $accounts = $this->createAccount($purchase);

        $packages = json_decode(request()->packages);
        $processed_packages = [];
        foreach ($accounts as $key => $account) {
            $processed_packages[$account->id]['total_price'] = $packages[$key]->price;
            $processed_packages[$account->id]['package_id'] = $packages[$key]->id;
            $processed_packages[$account->id]['amount'] = 1;
        }

        $packages = collect($processed_packages);

        $purchase->accounts()->attach($packages);

        return json_encode(['message' => 'purchase.success', 'purchase' => Purchase::with('packages')->find($purchase->id)]);
    }

    public function createAccount($purchase) {
        $parent_account = Account::where('referral_code', $purchase->referral_code)->where('account_level', '!=', 0)->first();
        $first_account = Account::where('user_id', $purchase->user_id)->where('account_level', 0)->first();
        $accountCollections = collect();
        $machine_count = 0;

        // create selected highest account
        if($first_account){
            $first_account->update([
                        'account_level' => $purchase->account_level,
                        'parent_id' => $parent_account ? $parent_account->id : $first_account->parent_id,
                        'machine_count' => $machine_count
                    ]);
        }

        if($parent_account || (!$first_account && !$parent_account)){
            $first_account = Account::create([
                        'user_id' => $purchase->user_id,
                        'account_level' => $purchase->account_level,
                        'referral_code' => $purchase->user->country_id,
                        'machine_count' => $machine_count
                    ]);
            $parent_account ? $first_account->appendToNode($parent_account)->save() : $first_account->saveAsRoot();
        }

        $accountCollections->push($first_account);
        
        $this->createBulkAccount($purchase, $purchase->user_id, $first_account->id, --$first_account->account_level, $accountCollections, $machine_count);

        return $accountCollections;
    }

    public function createBulkAccount($purchase, $user_id, $parent_id = null, $level = 1, $accountCollections, $machine_count) {
        $count = $level == 2 || $level == 1 ? 3 : 0;
        $intermediateCollection = collect();

        for($i = 0; $i < $count; $i++) {
            if($level == 2){
                $createdAccount = $this->createAccountQuery($purchase, $user_id, $level, $parent_id, $machine_count);
                $intermediateCollection->push($createdAccount);
                $accountCollections->push($createdAccount);
            }   

            if($level == 1)
                $accountCollections->push($this->createAccountQuery($purchase, $user_id, $level, $parent_id, $machine_count));
        };

        if($intermediateCollection) {
            $level -= 1;
            foreach($intermediateCollection as $intermediateAccount){
                for($i = 0; $i < $count; $i++) {
                    $accountCollections->push($this->createAccountQuery($purchase, $user_id, $level, $intermediateAccount->id, $machine_count));
                }
            } 
        }

        return $accountCollections;
    }

    public function createAccountQuery($purchase, $user_id, $level, $parent_id, $machine_count) {
        $account = Account::create([
                'user_id' => $user_id,
                'account_level' => $level,
                'referral_code' => $purchase->user->country_id,
                'parent_id' => $parent_id,   
                'machine_count' => $machine_count
            ]);

        return $account;
    }

    public function accountUpdate(Purchase $purchase)
    {
        $referral_account = Account::where('accounts.referral_code', $purchase->referral_code)->first();

        $purchase->update([
                'total_price' => request()->total_price,
                'created_at' => request()->purchase_date,
                'account_level' => request()->account_level,
                'referral_code' => request()->referral_code,
                'is_account' => true,
                'account_id' => null,
            ]);
        // update from buying account to account
        if($purchase->is_account) {
            
            $this->deleteBulkAccount($purchase, $referral_account);
            // ins
            $accounts = $this->createAccount($purchase);
            $packages = json_decode(request()->packages);
            
            $processed_packages = $this->processPackage($packages, $accounts);
            $packages = collect($processed_packages);
            $purchase->accounts()->attach($packages);    
        } else {
            $puchase->packages()->detach();

            $accounts = $this->createAccount($purchase, $referral_account);

            $packages = json_decode(request()->packages);
            $processed_packages = $this->processPackage($packages, $accounts);
            $packages = collect($processed_packages);
            $purchase->accounts()->attach($packages);    
        }

        if($purchase->payment)
            $purchase->payment->delete();

        $purchase->update([
                'status' => 'pending',
                'payment_id' => null,
            ]);

        return json_encode(['message' => 'purchase.updated', 'purchase' => Purchase::with('packages')->find($purchase->id)]);
    }

    public function deleteBulkAccount($purchase, $referral_account)
    {
        // get accounts
        $to_be_detele_accounts = collect();
        foreach ($purchase->accounts as $account) {
            if($account->id == $referral_account->id){
                $referral_account->update([
                    'account_level' => 0,
                ]);
            } else {
                $to_be_detele_accounts->push($account);
            }
        }
        // detach package_purchase and delete accounts
        $purchase->accounts()->detach();
        if($to_be_detele_accounts)
            DB::table('accounts')->whereIn('id', $to_be_detele_accounts->pluck('id'))->delete();
    }

    public function processPackage($packages, $accounts)
    {
        $processed_packages = [];
        foreach ($accounts as $key => $account) {
            $processed_packages[$account->id]['total_price'] = $packages[$key]->price;
            $processed_packages[$account->id]['package_id'] = $packages[$key]->id;
            $processed_packages[$account->id]['amount'] = 1;
        }
        return $processed_packages;
    }

    public function update(Purchase $purchase)
    {
        $referral_account = Account::where('accounts.referral_code', $purchase->referral_code)->first();
        
        if($purchase->is_account){
            $this->deleteBulkAccount($purchase, $referral_account);
        }
        $packages = json_decode(request()->packages);
        $processed_packages = [];
        foreach ($packages as $key => $package) {
            if($package->amount > 0) {
                // $db_package = Package::find($package->id);
                // $price = $db_package->price_promotion > 0 ? $db_package->price_promotion : $db_package->price;
                // $price_std = $db_package->price_std_promotion > 0 ? $db_package->price_std_promotion : $db_package->price_std;
                $processed_packages[$package->id]['total_price'] = $package->price * $package->amount;
                $processed_packages[$package->id]['total_price_std'] = $package->price_std * $package->amount;
                $processed_packages[$package->id]['amount'] = $package->amount;
            }
        }

        $packages = collect($processed_packages);

        $total_price = $packages->sum(function($package){
            return $package['total_price'];
        });

        $total_price_std = $packages->sum(function($package){
            return $package['total_price_std'];
        });

        if($purchase->payment)
            $purchase->payment->delete();

        $purchase->update([
            'total_price' => $total_price,
            'total_price_std' => $total_price_std,
            'created_at' => request()->purchase_date,
            'account_level' => null,
            'referral_code' => null,
            'is_account' => false,
            'account_id' => request()->account_id,
            'status' => 'pending',
            'payment_id' => null,
        ]);

        $purchase->packages()->sync($packages);

        return json_encode(['message' => 'purchase.updated', 'purchase' => Purchase::with('packages')->find($purchase->id)]);
    }

    public function verify(Purchase $purchase)
    { 
        // dd($purchase);
        if($purchase->referral_code) {
            $parent_account = Account::where('referral_code', $purchase->referral_code);
        }

        return $purchase->verify();      
    }

    public function delete(Purchase $purchase)
    {
        Log::info("Deleted purchase " . $purchase->user->name . " by " . auth()->user()->name);

        $purchase->packages()->detach();

        $purchase->payment()->delete();

        $purchase->delete();

        return json_encode(['message' => 'purchase.deleted']);
    }
}

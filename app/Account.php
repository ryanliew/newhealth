<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Country;

class Account extends Model
{
	use NodeTrait;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function purchases()
    {
        return $this->belongsToMany('App\Purchase')->withPivot('amount', 'total_price', 'account_id')->withTimestamps();
    }

    public function setReferralCodeAttribute($value)
    {   
        $country = Country::find($value);
        $this->attributes['referral_code'] = $this->generateReferralCode($country);
    }

    public function generateReferralCode($country)
    {
        $code = strtoupper($country->code) . rand(pow(10, 4), pow(10, 5)-1);
        if(User::where('referral_code', $code)->count() > 0)
            $code = $this->generateReferralCode($country);
        
        return $code;
    }

    public function getLeadershipBonusAttribute()
    {
        switch($this->account_level) {
            case 1:
                $percentage = 2;
                break;
            case 2: 
                $percentage = 3;
                break;
            case 3:
                $percentage = 5;
                break;
            default:
                $percentage = 0;
        }
        
        return $percentage / 100;
    }

    public function getCommisionPercentageAttribute()
    {
        switch($this->account_level) {
            case 1:
                $percentage = 20;
                break;
            case 2: 
                $percentage = 35;
                break;
            case 3:
                $percentage = 45;
                break;
            default:
                $percentage = 0;
        }
        
        return $percentage / 100;
    }

    public static function setReferralCode($value) {
        $account_model = new Account();
        $country = Country::find($value);
        return $account_model->generateReferralCode($country);
    }

    // Adjust user level
    public function adjust_level($is_account)
    {
       
        if($is_account){
            $number_of_children_with_lower_rank = $this->children()->where('accounts.account_level', $this->account_level)->where('is_verified', true)->count();

            if($number_of_children_with_lower_rank == 3 && $this->account_level < 3) {
                $this->update(['account_level' => ++$this->account_level]);
            }
        }

        if(!$is_account){
            $upgrade_level = 0;
            if($this->machine_count > 2)
                $upgrade_level = 1;

            if($this->account_level < $upgrade_level)
                $this->update(['account_level' => $upgrade_level]);
        }
    } 
}

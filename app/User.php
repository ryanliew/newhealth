<?php

namespace App;

use App\Country;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Kalnoy\Nestedset\NodeTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    protected $appends = ['address', 'company_address', 'default_locale', 'is_std', 'group_sale'];


    protected $with = ['package'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function package()
    {
        return $this->belongsTo('App\Package');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function getPersonalAddressAttribute()
    {
        return $this->addresses()->where('type', Address::PERSONAL())->first();
    }

    public function getCompanyAddressAttribute()
    {
        return $this->addresses()->where('type', Address::COMPANY())->first();
    }

    public function setCountryIdAttribute($value)
    {
        $this->attributes['country_id'] = $value;
        
        $country = Country::find($value);

        $this->attributes['referral_code'] = $this->generateReferralCode($country);
    }

    public function getDefaultLocaleAttribute()
    {
        return $this->country->default_locale;
    }

    public function getAddressAttribute()
    {
        if($this->personal_address) {
            return $this->personal_address->display;
        }

        return '';
    }

    public function getCompanyAddressDisplayAttribute()
    {
        if($this->personal_address) {
            return $this->company_address->display;
        }

        return '';
    }

    public function getContactNumberAttribute()
    {
        return $this->personal_address ? $this->personal_address->phone : "";
    }

    public function getIsStdAttribute()
    {
        return !($this->country->name == "Malaysia" || $this->country->name == "Singapore");
    }


    public function getGroupSaleAttribute()
    {
        return $this->descendants()->sum('tree_count') + $this->tree_count;
    }


    public function generateReferralCode($country)
    {
        $code = strtoupper($country->code) . rand(pow(10, 4), pow(10, 5)-1);

        if(User::where('referral_code', $code)->count() > 0)
            $code = $this->generateReferralCode($country);

        return $code;
    }


    public function getCommisionPercentageAttribute()
    {

        switch($this->user_level) {
            case 1:
                $percentage = 8;
                break;
            case 2: 
                $percentage = 10;
                break;
            case 3:
                $percentage = 15;
                break;
            case 4:
                $percentage = 20;
                break;
            default:
                $percentage = 0;
        }
        
        return $percentage / 100;
    }

    // Adjust user level
    public function adjust_level()
    {
        $number_of_children = $this->children->count();
        $number_of_group_sales = $this->group_sale;

        $user_level = 1;

        if($number_of_group_sales >= 200 && $number_of_children >= 10)
        {
            $user_level = 4;
        }
        else if($number_of_group_sales >= 100 && $number_of_children >= 5)
        {
            $user_level = 3;
        }
        else if($number_of_children >= 3)
        {
            $user_level = 2;
        }

        if($this->user_level < $user_level)
            $this->update(['user_level' => $user_level]);
    }

    // Reset password
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($this, App::getLocale(), $token));
    }
}

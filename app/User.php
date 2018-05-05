<?php

namespace App;

use App\Country;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'social_id', 'social_service', 'referral_code', 'country_id'
    ];

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

    public function setCountryIdAttribute($value)
    {
        $this->attributes['country_id'] = $value;
        
        $country = Country::find($value);

        $this->attributes['referral_code'] = $this->generateReferralCode($country);
    }

    public function generateReferralCode($country)
    {
        $code = strtoupper($country->code) . '-' .  Carbon::now()->month . round(Carbon::now()->micro / 1000) . Carbon::now()->second . Carbon::now()->day;

        return $code;
    }
}

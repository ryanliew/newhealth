<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public static function PERSONAL()
    {
    	return 'personal';
    }

    public static function COMPANY()
    {
    	return 'company';
    }
}

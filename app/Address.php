<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $guarded = [];

    protected $appends = ['display'];
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public function getDisplayAttribute()
    {
        $display = $this->line_1 . ", " . $this->line_2;
        $display .= $this->line_2 ? ", " : ""; 
        $display .= $this->country->name . ", " . $this->postcode;
        return $display;
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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $guarded = [];
	
    public function purchase()
    {
    	return $this->hasOne('App\Purchase');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}

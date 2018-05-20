<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function packages()
    {
    	return $this->belongsToMany('App\Package')->withPivot('amount', 'total_price')->withTimestamps();
    }

    public function verify()
    {
    	$this->update(['status' => 'complete', 'is_verified' => true]);

        return $this;
    }
}

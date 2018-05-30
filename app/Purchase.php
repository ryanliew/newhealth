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
    	return $this->belongsToMany('App\Package')->withPivot('amount', 'total_price', 'total_price_rmb')->withTimestamps();
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }

    public function verify()
    {
    	$this->update(['status' => 'complete', 'is_verified' => true]);

        $tree_count = $this->user->tree_count;

        $this->user()->update([ 'tree_count' => $tree_count + $this->packages->sum(function($package){ return $package->pivot->amount * $package->tree_count; }) ]);
        return $this;
    }
}

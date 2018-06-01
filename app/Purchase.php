<?php

namespace App;

use App\Transaction;
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
    	return $this->belongsToMany('App\Package')->withPivot('amount', 'total_price', 'total_price_std')->withTimestamps();
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

        if(!is_null($this->user->parent_id)) {
            $parent = $this->user->parent;
            
            $transaction = Transaction::create($this->user->parent, 
                                                Transaction::TYPE_COMMISION(), 
                                                Transaction::DESCRIPTION_TREE_PURCHASE(), 
                                                $this->total_price * $parent->commision_percentage, 
                                                $this->total_price_std * 
                                                $parent->commision_percentage, 
                                                $this->is_std, 
                                                $this->created_at);

            $transaction->update(['purchase_id' => $this->id, 'target_id' => $this->user_id]);
        }

        foreach(User::ancestorsAndSelf($this->user_id) as $user)
        {
            $user->adjust_level();
        }
        
        return $this;
    }
}

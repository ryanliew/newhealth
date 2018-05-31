<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
  	protected $guarded = ['id', 'created_at', 'updated_at'];

  	public function purchases()
  	{
  		return $this->belongsToMany('App\Purchase')->withPivot('amount', 'total_price', 'total_price_std')->withTimestamps();
  	}
}

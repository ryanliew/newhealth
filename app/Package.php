<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
  	protected $guarded = ['id', 'created_at', 'updated_at'];

  	public function purchases()
  	{
  		return $this->belongsToMany('App\Purchase')->withPivot('amount', 'total_price', 'total_selling_price', 'total_price_std', 'account_id')->withTimestamps();
  	}

  	public function redemption()
    {
        return $this->hasOne('App\Redemption');
    }

    public function getAccountLevelLabel($accountLevel)
    {
    	$label = '';
    	switch($accountLevel){
    		case 1:
    			$label = 'Silver';
    			break;
    		case 2:
    			$label = 'Platinum';
    			break;
    		case 3:
    			$label = 'Diamond';
    			break;
    	}
    	return $label;
    }
}

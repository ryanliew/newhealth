<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function target()
	{
		return $this->belongsTo('App\User', 'target_id');
	}

	public function purchase()
	{
		return $this->belongsTo('App\Purchase');
	}

	/**
	 * Create a transaction
	 * @param  User   $user        Owner of the transaction
	 * @param  String $type        commision type from Transaction statics
	 * @param  String $description transaction description from Transaction statics
	 * @param  String $amount      Amount to give in MYR
	 * @param  String $amount_std  Amount to give in USD
	 * @param  Boolean $is_std	   Indicate whether the transaction is standard pricing or non standard pricing
	 * @param  Date $date          The date of the operation that causes this transaction
 	 * @return Transaction         Created transaction
	 */
	public static function create(User $user, $type, $description, $amount, $amount_std, $is_std, $date)
	{
	 	$transaction = $user->transactions()->create([
	 		'type' => $type,
	 		'description' => $description,
	 		'amount' => $amount,
	 		'amount_std' => $amount_std,
	 		'is_std' => $is_std,
	 		'date' => $date
	 	]);

	 	return $transaction;
	} 

	public static function TYPE_COMMISION()
	{
		return 'one_time_commision';
	}

	public static function DESCRIPTION_TREE_PURCHASE()
	{
		return 'tree_purchase';
	}
}

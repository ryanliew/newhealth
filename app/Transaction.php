<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
	use SoftDeletes;

	protected $guarded = [];

	protected $dates = ['date', 'deleted_at'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function target()
	{
		return $this->belongsTo('App\Account', 'target_id');
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
	 * @param  Float $percentage   The percentage of the commission amount for debugging purpose
 	 * @return Transaction         Created transaction
	 */
	public static function create(User $user, $type, $description, $amount, $amount_std, $is_std, $date, $percentage)
	{
	 	$transaction = $user->transactions()->create([
	 		'type' => $type,
	 		'description' => $description,
	 		'amount' => $amount,
	 		'amount_std' => $amount_std,
	 		'is_std' => $is_std,
	 		'date' => $date,
	 		'percentage' => $percentage
	 	]);

	 	return $transaction;
	} 

	public static function ONE_TIME_COMMISSION()
	{
		return 'one_time_commision';
	}

	public static function E_WALLET_COMMISSION()
	{
		return 'e_wallet_commision';
	}

	public static function E_WALLET_LEADERSHIP_BONUS()
	{
		return 'e_wallet_leadership_bonus';
	}

	public static function LEADERSHIP_BONUS()
	{
		return 'leadership_bonus';
	}

	public static function DESCRIPTION_MACHINE_PURCHASE()
	{
		return 'machine_purchase';
	}

	public static function STATUS_PAID()
	{
		return 'paid';
	}
}

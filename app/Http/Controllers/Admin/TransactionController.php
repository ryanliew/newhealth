<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
    	$date = Carbon::parse(request()->month);

    	if(!request()->month)
    		$date = Carbon::now();

    	$query = DB::table('users')
    				->leftJoin('transactions', 'users.id', '=', 'transactions.user_id')
    				->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', [$date->month, $date->year])
    				->where('transactions.is_std', 0)
    				->select(DB::raw('sum(transactions.amount) as amount, user_id,name, bank_name, account_no, bank_address, bank_swift'))
    				->groupBy('users.id')
    				->orderByDesc('amount');

    	return Controller::VueTableListResult($query);
    }

    public function index_standard()
    {
    	$date = Carbon::parse(request()->month);

    	if(!request()->month)
    		$date = Carbon::now();

    	$query = DB::table('users')
    				->leftJoin('transactions', 'users.id', '=', 'transactions.user_id')
    				->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', [$date->month, $date->year])
    				->where('transactions.is_std', 1)
    				->select(DB::raw('sum(transactions.amount_std) as amount_std, user_id, name, bank_name, account_no, bank_address, bank_swift'))
    				->groupBy('users.id')
    				->orderByDesc('amount_std');

    	return Controller::VueTableListResult($query);
    }
}

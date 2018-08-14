<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Transaction;
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
    				->select(DB::raw('sum(transactions.amount) as amount, user_id, name, bank_name, account_no, bank_address, bank_swift, payout_status, is_std'))
                    ->groupBy('users.id', 'transactions.payout_status', 'is_std')
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
    				->select(DB::raw('sum(transactions.amount_std) as amount_std, user_id, name, bank_name, account_no, bank_address, bank_swift, payout_status, transactions.is_std'))
    				->groupBy('users.id', 'transactions.payout_status', 'transactions.is_std')
    				->orderByDesc('amount_std');

    	return Controller::VueTableListResult($query);
    }

    public function pay()
    {
        $date = Carbon::parse(request()->month);

        if(!request()->month)
            $date = Carbon::now();

        $month = str_pad($date->month, 2, "0", STR_PAD_LEFT);
        $year = $date->year . "";

        $transactions = Transaction::where('user_id', request()->user_id)
                                    ->where('is_std', request()->has('is_std'))
                                    ->whereMonth('date', $month)
                                    ->whereYear('date', $year)
                                    ->update([
                                        "payout_status" => Transaction::STATUS_PAID(),
                                        "remark" => request()->remark
                                    ]);

        return json_encode(['message' => 'transaction.status_updated']);
    }               
}

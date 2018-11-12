<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return Controller::VueTableListResult(Purchase::with('packages', 'payment', 'accounts')
                                                    ->select('purchases.id as id',
                                                            'payment_id',
                                                            'purchases.created_at as created_at',
                                                            'purchases.total_price as total_price',
                                                            'purchases.status as status',
                                                            'purchases.is_std as is_std',
                                                            'purchases.total_price_std as total_price_std',
                                                            'purchases.account_level as account_level',
                                                            'purchases.account_id as account_id',
                                                            'purchases.referral_code as referral_code',
                                                            'purchases.is_account as is_account',
                                                            'user_id',
                                                            'users.name as user_name')
                                                    ->leftJoin('users', 'users.id', '=', 'user_id')
                                                );
    }

    public function indexPending()
    {
        return Controller::VueTableListResult(Purchase::with('packages', 'payment', 'accounts')
                                                    ->select('purchases.id as id',
                                                            'payment_id',
                                                            'purchases.created_at as created_at',
                                                            'purchases.total_price as total_price',
                                                            'purchases.status as status',
                                                            'purchases.is_std as is_std',
                                                            'purchases.total_price_std as total_price_std',
                                                            'purchases.account_level as account_level',
                                                            'purchases.account_id as account_id',
                                                            'purchases.referral_code as referral_code',
                                                            'purchases.is_account as is_account',
                                                            'user_id',
                                                            'users.name as user_name')
                                                    ->where('status', 'pending_verification')
                                                    ->leftJoin('users', 'users.id', '=', 'user_id')
                                                );
    }
}

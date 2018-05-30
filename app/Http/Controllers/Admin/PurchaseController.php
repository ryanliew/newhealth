<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
     public function index()
    {
        return Controller::VueTableListResult(Purchase::with('packages', 'payment')
                                                    ->select('purchases.id as id',
                                                            'payment_id',
                                                            'purchases.created_at as created_at',
                                                            'purchases.total_price as total_price',
                                                            'purchases.status as status',
                                                            'purchases.is_rmb as is_rmb',
                                                            'purchases.total_price_rmb as total_price_rmb',
                                                            'users.name as user_name')
                                                    ->leftJoin('users', 'users.id', '=', 'user_id')
                                                );
    }
}

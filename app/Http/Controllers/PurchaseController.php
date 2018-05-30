<?php

namespace App\Http\Controllers;

use App\Package;
use App\Purchase;
use App\User;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(User $user)
    {
        return Controller::VueTableListResult($user->purchases()->with('packages', 'payment')
                                                    ->select('purchases.id as id',
                                                            'payment_id',
                                                            'purchases.created_at as created_at',
                                                            'purchases.total_price as total_price',
                                                            'purchases.total_price_rmb as total_price_rmb',
                                                            'purchases.is_rmb as is_rmb',
                                                            'purchases.status as status',
                                                            'users.name as user_name')
                                                    ->leftJoin('users', 'users.id', '=', 'user_id')
                                                );
    }

    public function store()
    {
    	$packages = json_decode(request()->packages);
        $processed_packages = [];
    	foreach ($packages as $key => $package) {
            if($package->amount > 0) {
                $db_package = Package::find($package->id);
                $processed_packages[$package->id]['total_price'] = $db_package->price * $package->amount;
                $processed_packages[$package->id]['total_price_rmb'] = $db_package->price_rmb * $package->amount;
                $processed_packages[$package->id]['amount'] = $package->amount;
            }
    	}

    	$packages = collect($processed_packages);

    	$total_price = $packages->sum(function($package){
    		return $package['total_price'];
    	});

        $total_price_rmb = $packages->sum(function($package){
            return $package['total_price_rmb'];
        });

    	$purchase = Purchase::create([
    		'user_id' => request()->user_id,
    		'total_price' => $total_price,
            'total_price_rmb' => $total_price_rmb,
            'is_rmb' => User::find(request()->user_id)->country_id == 48
    	]);

    	$purchase->packages()->sync($packages);

    	return json_encode(['message' => 'purchase.success', 'purchase' => Purchase::with('packages')->find($purchase->id)]);
    }

    public function verify(Purchase $purchase)
    { 
        return $purchase->verify();      
    }
}

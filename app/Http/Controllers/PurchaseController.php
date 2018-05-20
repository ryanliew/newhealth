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
        return Controller::VueTableListResult($user->purchases());
    }

    public function store()
    {
    	$packages = json_decode(request()->packages);
        $processed_packages = [];
    	foreach ($packages as $key => $package) {
            if($package->amount > 0) {
                $processed_packages[$package->id]['total_price'] = Package::find($package->id)->price * $package->amount;
                $processed_packages[$package->id]['amount'] = $package->amount;
            }
    	}

    	$packages = collect($processed_packages);

    	$total_price = $packages->sum(function($package){
    		return $package['total_price'];
    	});


    	$purchase = Purchase::create([
    		'user_id' => request()->user_id,
    		'total_price' => $total_price
    	]);

    	$purchase->packages()->sync($packages);

    	return $purchase;
    }

    public function verify(Purchase $purchase)
    {
        return $purchase->verify();      
    }	
}

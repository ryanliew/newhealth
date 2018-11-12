<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
    	return Package::where('can_upgrade', false)->where('can_redeem', false)->get();
    }

    public function getAccountPackages()
    {
    	return Package::where('can_upgrade', true)->where('can_redeem', false)->get();
    }
}

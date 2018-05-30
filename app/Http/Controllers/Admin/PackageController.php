<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
	public function validatePackage()
	{
		$messages = [
            'tree_count.required' => 'package.tree_count_required',
            'tree_count.numeric' => 'package.tree_count_invalid',
            'price.required' => 'package.price_required',
            'price_rmb.required' => 'package.price_rmb_required',
            'price.numeric' => 'package.price_invalid',
            'price_rmb.numeric' => 'package.price_rmb_numeric'
        ];

        request()->validate([
            'price' => 'required|numeric',
            'price_rmb' => 'required|numeric',
            'tree_count' => 'required|numeric'
        ], $messages);
	}

	public function store()
	{
		$this->validatePackage();

		Package::create(request()->all());

		return $this->message("package.create_success");
	}

    public function update(Package $package)
    {
    	$this->validatePackage();

    	$package->update(request()->all());

    	return $this->message("package.update_success");
    }

    public function index()
    {
    	return Controller::VueTableListResult(Package::where('id', '>', 0));
    }
}

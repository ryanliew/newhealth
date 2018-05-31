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
            'price_std.required' => 'package.price_std_required',
            'price.numeric' => 'package.price_invalid',
            'price_std.numeric' => 'package.price_std_numeric'
        ];

        request()->validate([
            'price' => 'required|numeric',
            'price_std' => 'required|numeric',
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

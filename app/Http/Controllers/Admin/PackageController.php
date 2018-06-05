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
            'tree_count.required' => 'validation.required',
            'tree_count.numeric' => 'validation.numeric',
            'price.required' => 'validation.required',
            'price_std.required' => 'validation.required',
            'price.numeric' => 'validation.numeric',
            'price_std.numeric' => 'validation.numeric'
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

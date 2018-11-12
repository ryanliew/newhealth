<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
	public function validatePackage()
	{
		$messages = [
            // 'tree_count.required' => 'validation.required',
            // 'tree_count.numeric' => 'validation.numeric',
            'price.required' => 'validation.required',
            'package_description.required' => 'validation.required',
            'package_name.required' => 'validation.required',
            'price.numeric' => 'validation.numeric',
            'package_picture.required' => 'package.invalid_package_picture',
            'package_picture.file' => 'package.invalid_package_picture',
            'package_picture.max' => 'package.package_picture_exceed_size',
            // 'price_std.numeric' => 'validation.numeric',
            // 'price_std.required' => 'validation.required',
        ];

        request()->validate([
            'price' => 'required|numeric',
            'package_name' => 'required',
            'package_description' => 'required',
            'package_picture' => ['required', 'file', 'max:8000']
            // 'price_std' => 'required|numeric',
            // 'tree_count' => 'required|numeric'
        ], $messages);
	}

	public function store()
	{
		$this->validatePackage();

        $package = Package::create(['name' => request()->package_name,
                                    'price' => request()->price,
                                    'description' => request()->package_description,
                                    'can_upgrade' => request()->can_upgrade == 'true' ? true : false,
                                    'can_redeem' => request()->can_redeem == 'true' ? true : false,
                                    'package_photo_path' => request()->file('package_picture')->store('packages', 'public'),
                                    'machine_count' => request()->machine_count ? request()->machine_count : 0
                                    ]);

		// Package::create(request()->all());

		return $this->message("package.create_success");
	}

    public function update(Package $package)
    {
        $message = [
            'price.required' => 'validation.required',
            'package_description.required' => 'validation.required',
            'package_name.required' => 'validation.required',
            'price.numeric' => 'validation.numeric',
            'package_picture.file' => 'package.invalid_package_picture',
            'package_picture.max' => 'package.package_picture_exceed_size',
        ];

        request()->validate([
            'price' => 'required|numeric',
            'package_name' => 'required',
            'package_description' => 'required',
            'package_picture' => ['max:8000']
            // 'price_std' => 'required|numeric',
            // 'tree_count' => 'required|numeric'
        ], $message);

        $photo_path = $package->package_photo_path;

        if(request()->hasFile('package_picture'))
        {
            Storage::disk('public')->delete($package->package_photo_path);
            $photo_path = request()->file('package_picture')->store('packages', 'public');
        }

    	$package->update(['name' => request()->package_name,
                            'price' => request()->price,
                            'description' => request()->package_description,
                            'can_upgrade' => request()->can_upgrade ? true : false,
                            'can_redeem' => request()->can_redeem ? true : false,
                            'package_photo_path' => $photo_path,
                            'machine_count' => request()->machine_count ? request()->machine_count : 0
                            ]);

    	return $this->message("package.update_success");
    }

    public function index()
    {
    	return Controller::VueTableListResult(Package::where('id', '>', 0));
    }
}

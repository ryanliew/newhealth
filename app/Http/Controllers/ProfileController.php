<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
    	return json_encode($request->user());
    }

    public function update()
    {
    	$validated = request()->validate([
    		'email' => 'sometimes|required|email',
    		'referrer_code' => 'exists:users,referral_code',
    		'country_id' => 'sometimes|required|exists:countries,id'
    	]);

    	if(isset($validated['referrer_code']))
    	{
    		$referrer = User::where('referral_code', $validated['referrer_code'])->first();
    		auth()->user()->appendToNode($referrer)->save();
    	}

    	$user = auth()->user()->update($validated);

    	return back();
    }
}

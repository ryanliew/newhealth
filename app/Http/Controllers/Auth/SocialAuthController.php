<?php

namespace App\Http\Controllers\Auth;

use App\AuthenticateUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    public function login(AuthenticateUser $authenticateUser, $service)
    {
    	return $authenticateUser->execute($service, request()->has('code'), $this);
    	//return Socialite::with($service)->redirect();
    }

    public function userHasLoggedIn($user)
    {
    	if(isset($user->identification))
    		return redirect()->route('register.complete');

    	return redirect()->route('register.success');
    }
}

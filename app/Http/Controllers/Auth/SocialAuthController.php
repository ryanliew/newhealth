<?php

namespace App\Http\Controllers\Auth;

use App\AuthenticateUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SocialAuthController extends Controller
{
    public function login(AuthenticateUser $authenticateUser, $service)
    {
        if(request()->has('referrer'))
        {
            session(['referrer', request()->referrer]);
        }

    	return $authenticateUser->execute($service, request()->has('code'), $this);
    	//return Socialite::with($service)->redirect();
    }

    public function userHasLoggedIn($user, $state)
    {
    	if(!is_null($user->identification)) {
    		return redirect()->route('home');
        }
    	return redirect()->route('register.success')->with('referrer', session('referrerId'));
    }
}

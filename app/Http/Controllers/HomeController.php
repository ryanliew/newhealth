<?php

namespace App\Http\Controllers;

use App\Country;
use App\Package;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(auth()->user()->identification);
        if(is_null(auth()->user()->identification ))
            return redirect()->route('register.success');

        // return redirect()->route('register.complete');
        return view('app');
    }

    /**
     * Show the thank you page after register success
     * @return \Illuminate\Http\Response
     */
    public function thankyou()
    {
        if(is_null(auth()->user()->identification))
            return view('success', ['countries' => Country::all(), 'packages' => Package::all()]);

        return redirect()->route('home');
    }

    public function getReferrer()
    {
        //return request()->code;
        $referrer = User::where('referral_code', request()->code)->get()->first();

        return $referrer ? $referrer->name : 'auth.referrer_not_found';
    }
}

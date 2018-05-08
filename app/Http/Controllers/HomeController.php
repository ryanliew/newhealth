<?php

namespace App\Http\Controllers;

use App\Country;
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
        return view('welcome');
    }

    /**
     * Show the thank you page after register success
     * @return \Illuminate\Http\Response
     */
    public function thankyou()
    {
        return view('thankyou', ['countries' => Country::all()]);
    }

    public function getReferrer()
    {
        //return request()->code;
        $referrer = User::where('referral_code', request()->code)->get()->first();

        return $referrer ? $referrer->name : 'auth.referrer-not-found';
    }
}

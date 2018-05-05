<?php

namespace App\Http\Controllers;

use App\Country;
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
}

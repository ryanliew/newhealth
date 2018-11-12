<?php

namespace App\Http\Controllers;

use App\Country;
use App\Package;
use App\Post;
use App\User;
use App\Account;
use App\Ewallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if(!auth()->user())
        {
            return redirect("/");
        }

        if(auth()->user()->is_locked)
        {
            Auth::logout();
            return redirect("/login")->with('message', "user.locked_message");
        }

        if(auth()->user()->user_status == 'pending')
        {
            Auth::logout();
            return redirect("/login")->with('message', "user.waiting_approval");
        }        
        
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

    public function getUserByReferralCode()
    {
        //return request()->code;
        $referrer = User::where('referral_code', request()->code)->get()->first();

        return $referrer ? $referrer->name : 'auth.referrer_not_found';
    }

    public function getReferrer()
    {
        $referrer = Account::where('referral_code', request()->code)->get()->first();

        return $referrer ? $referrer->name : 'auth.referrer_not_found';
    }

    public function getAncestor()
    {
        $ancestor = Account::where('user_id', auth()->user()->id)->where('parent_id', null)->first();

        return $ancestor ? $ancestor->referral_code : 'auth.ancestor_not_found';
    }

    public function cancel()
    {
        Auth::logout();
        return redirect()->route('register');
    }

    public function dashboard(User $user)
    {
        $data = array();

        $account_array = array();

        $data['trees_sold'] = User::sum("tree_count");

        $data['e_wallet'] = $user->ewallet;

        $data['accounts'] = $user->accounts;

        foreach($user->accounts as $account){
            $qualification_accounts = collect();
            $number_of_children = 0;
            
            if($account->account_level == 0)
                $number_of_children = $account->machine_count;
            else
                $number_of_children = Account::where('parent_id', $account->id)->where('account_level', $account->account_level)->count();

            $qualification_accounts->put('account_level', $account->account_level);
            $qualification_accounts->put('number_of_children', $number_of_children);
            $account_array[$account->referral_code] = $qualification_accounts;   
        }

        $data['qualification'] = $account_array;

        $data['descendants'] = $user->descendants;

        $data['posts'] = Post::latest()->limit(5)->get();

        return json_encode($data);
    }
}

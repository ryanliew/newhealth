<?php

namespace App\Http\Controllers;

use App\Account;
use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sellAccount()
    {
        $user = User::where('referral_code', request()->referral_code)->first();
        $account = Account::find(request()->account_id);

        $transaction = new Transaction;
        $transaction->user_id = $user->id;
        $transaction->amount = '0.00';
        $transaction->type = 'sell_account';
        $transaction->description = 'Account sell by ' . $account->user->name;
        $transaction->target_id = $account->id;
        $transaction->date = Carbon::now();
        $transaction->save();

        $account->update(['user_id' => $user->id]);

        return json_encode(['message' => 'resell.verify_success', 'account' => $account]);
    }

    public function getAuthAccounts($user_id = null)
    {
        if($user_id == null){
            $authAccounts = Account::all();
        }
        else{
            $authAccounts = Account::where('user_id', $user_id)->where('is_verified', true)->get();
        }
        
        return $authAccounts ? $authAccounts : 'auth.ancestor_not_found';
    }

    public function getTree(User $user)
    {   
        
        $accounts = $user->accounts->where('parent_id', null);
        $accountDetails = collect();
        foreach($accounts as $key => $account)
        {
            $accountDetails->push(Account::descendantsAndSelf($account->id));
        }

        return Account::with('user')->whereIn('id', $accountDetails->flatten()->pluck('id'))->get()->toTree();
    }
}

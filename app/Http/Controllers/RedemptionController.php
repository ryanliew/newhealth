<?php

namespace App\Http\Controllers;

use App\Notifications\RedemptionCreateNotification;
use App\Package;
use App\Redemption;
use App\Ewallet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class RedemptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return Controller::VueTableListResult($user->redemptions()->with('package')
                                                ->select('redemptions.id',
                                                        'redemptions.package_quantity',
                                                        'redemptions.total',
                                                        'redemptions.status',
                                                        'redemptions.created_at',
                                                        'user_id',
                                                        'users.name',
                                                        'redemptions.package_id')
                                                ->leftJoin('users', 'users.id', '=', 'user_id'));
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
        $redemption = Redemption::create([
            'user_id' => $request->user_id,
            'package_id' => $request->package_id,
            'package_quantity' => $request->quantity,
            'total' => $request->total,
        ]);

        $wallet = Ewallet::where('user_id', $request->user_id)->first();
        $wallet->update([
            'amount' => $wallet->amount - $request->total,
        ]);

        Notification::send($redemption->user, new RedemptionCreateNotification($redemption));

        return json_encode(['message' => 'redemption.success', 'redemption' => $redemption]);
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

    public function approve(Redemption $redemption)
    {
        if($redemption->status != 'pending')
            return json_encode(['message' => 'redemption.redemption_approve', 'redemption' => $redemption]);

        $redemption->update([
            'status' => 'approved',
        ]);
    
        return json_encode(['message' => 'redemption.redemption_approve', 'redemption' => $redemption]);
    }

    public function reject(Redemption $redemption)
    {
        $redemption->update([
            'status' => 'rejected',
            'reject_reason' => request()->reject_note,
        ]);

        $wallet = Ewallet::where('user_id', $redemption->user_id)->first();
        $wallet->update([
            'amount' => $wallet->amount + $redemption->total
        ]);
    
        return json_encode(['message' => 'redemption.redemption_success', 'redemption' => $redemption]);
    }

    public function cancel(Redemption $redemption)
    {
        $redemption->update([
            'status' => 'canceled',
        ]);

        $wallet = Ewallet::where('user_id', $redemption->user_id)->first();
        $wallet->update([
            'amount' => $wallet->amount + $redemption->total
        ]);

        return json_encode(['message' => 'redemption.redemption_cancel', 'redemption' => $redemption]);
    }

    public function getEwalletAmount($user_id)
    {
        return Ewallet::where('user_id', $user_id)->first();
    }

    public function items()
    {
        return Controller::VueTableListResult(Package::where('can_upgrade', false)->where('can_redeem', true));
    }
}

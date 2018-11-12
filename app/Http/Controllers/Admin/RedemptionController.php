<?php

namespace App\Http\Controllers\Admin;

use App\Redemption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedemptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Controller::VueTableListResult(Redemption::with('package')
                                                ->select('redemptions.id',
                                                        'redemptions.package_quantity',
                                                        'redemptions.total',
                                                        'redemptions.created_at',
                                                        'redemptions.status',
                                                        'user_id',
                                                        'users.name',
                                                        'redemptions.package_id')
                                                ->leftJoin('users', 'users.id', '=', 'user_id'));
    }

    public function indexPending()
    {
        return Controller::VueTableListResult(Redemption::with('package')
                                                ->select('redemptions.id',
                                                        'redemptions.package_quantity',
                                                        'redemptions.total',
                                                        'redemptions.created_at',
                                                        'redemptions.status',
                                                        'user_id',
                                                        'users.name',
                                                        'redemptions.package_id')
                                                ->where('status', 'pending')
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
}

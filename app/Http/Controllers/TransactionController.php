<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(User $user)
    {
    	return Controller::VueTableListResult($user
    										->transactions()
    										->latest()
    										->with('target'));
    }
}

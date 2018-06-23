<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
    	return Controller::VueTableListResult(User::with('contacts'));
    }

    public function indexPending()
    {
    	return Controller::VueTableListResult(User::with('contacts')->where('id_status', '<>', 'complete'));
    }

    public function getTree()
    {
    	return User::all()->toTree();
    }
}

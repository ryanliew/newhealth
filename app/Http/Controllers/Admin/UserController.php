<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
    	return Controller::VueTableListResult(User::with('contacts'), !request()->has('all'), ['transaction_start', 'transaction_end']);
    }

    public function indexPending()
    {
    	return Controller::VueTableListResult(User::with('contacts')->where('id_status', '<>', 'complete'));
    }

    public function getTree()
    {
    	return User::all()->toTree();
    }

    public function getParents()
    {
        $users = User::all();

        $results = $users->diff(User::descendantsAndSelf(request()->user_id));

        return json_encode($results);
    }

    public function settings(User $user)
    {
        $user->update([
            'user_level' => request()->user_level,
            'is_admin' => request()->has('is_admin')
        ]);

        if(request()->has('parent_id') && $user->parent_id !== request()->parent_id)
        {
            $parent = User::find(request()->parent_id);

            $parent->appendNode($user);
        }
        else if(!request()->has('parent_id') && !is_null($user->parent_id))
        {
            $user->saveAsRoot();
        }

        return json_encode(['message' => 'user.setting_success']);
    }
}

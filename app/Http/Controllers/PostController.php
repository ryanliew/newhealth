<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Notifications\NewsletterNotification;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Controller::VueTableListResult(Post::latest());
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
        request()->validate(['title' => 'required']);

        Post::create([
            'title' => request()->title, 
            'content' => request()->content, 
            'user_id' => request()->user, 
            'title_zh' => request()->title_zh, 
            'content_zh' => request()->content_zh,
            'cover_photo' => request()->file('cover_photo')->store('posts', 'public'),
            'left_photo' => request()->file('left_photo')->store('posts', 'public')
        ]);

        return json_encode(['message' => 'post.create_success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        request()->validate(['title' => 'required']);

        $left_photo = $post->left_photo;
        $cover_photo = $post->cover_photo;

        if(request()->hasFile('cover_photo'))
        {
            Storage::disk('public')->delete($post->cover_photo);
            $cover_photo = request()->file('cover_photo')->store('posts', 'public');
        }

        if(request()->hasFile('left_photo'))
        {
            Storage::disk('public')->delete($post->left_photo);
            $left_photo = request()->file('left_photo')->store('posts', 'public');
        }

        $post->update([
            'title' => request()->title, 
            'content' => request()->content, 
            'title_zh' => request()->title_zh, 
            'content_zh' => request()->content_zh,
            'left_photo' => $left_photo,
            'cover_photo' => $cover_photo
        ]);

        return json_encode(['message' => 'post.update_success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function notify(Post $post)
    {
        $users = User::where('is_admin', 0)->get();

        foreach($users as $user)
        {
            $user->notify(new NewsletterNotification($post, $user));
        }

        return json_encode(['message' => 'post.notify_success']);
    }

    public function notifyAdmin(Post $post)
    {
        $users = User::where('is_admin', 1)->get();

        foreach($users as $user)
        {
            $user->notify(new NewsletterNotification($post, $user));
        }

        return json_encode(['message' => 'post.notify_admins_success']);
    }
}

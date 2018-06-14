<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Notifications\NewsletterNotification;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

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
            'left_caption' => request()->left_caption,
            'right_caption' => request()->right_caption,
            'middle_caption' => request()->middle_caption,
            'left_caption_zh' => request()->left_caption_zh,
            'right_caption_zh' => request()->right_caption_zh,
            'middle_caption_zh' => request()->middle_caption_zh,
            'cover_photo' => request()->file('cover_photo')->store('posts', 'public'),
            'left_photo' => request()->hasFile('left_photo') ? $this->cropAndStorePhoto(request()->file('left_photo')) : '',
            'right_photo' => request()->hasFile('right_photo') ? $this->cropAndStorePhoto(request()->file('right_photo')) : '',
            'middle_photo' => request()->hasFile('middle_photo') ? $this->cropAndStorePhoto(request()->file('middle_photo')) : '',

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
        $middle_photo = $post->middle_photo;
        $right_photo = $post->right_photo;

        if(request()->hasFile('cover_photo'))
        {
            Storage::disk('public')->delete($post->cover_photo);
            $cover_photo = request()->file('cover_photo')->store('posts', 'public');
        }

        if(request()->hasFile('left_photo'))
        {
            $file = request()->file('left_photo');
            $left_photo = $this->cropAndStorePhoto($file, $post->left_photo);
        }

        if(request()->hasFile('middle_photo'))
        {
            $file = request()->file('middle_photo');
            $middle_photo = $this->cropAndStorePhoto($file, $post->middle_photo);
        }

        if(request()->hasFile('right_photo'))
        {
            $file = request()->file('right_photo');
            $right_photo = $this->cropAndStorePhoto($file, $post->right_photo);
        }

        $post->update([
            'title' => request()->title, 
            'content' => request()->content, 
            'title_zh' => request()->title_zh, 
            'content_zh' => request()->content_zh,
            'left_caption' => request()->left_caption,
            'right_caption' => request()->right_caption,
            'middle_caption' => request()->middle_caption,
            'left_caption_zh' => request()->left_caption_zh,
            'right_caption_zh' => request()->right_caption_zh,
            'middle_caption_zh' => request()->middle_caption_zh,
            'left_photo' => $left_photo,
            'cover_photo' => $cover_photo,
            'right_photo' => $right_photo,
            'middle_photo' => $middle_photo
        ]);

        return json_encode(['message' => 'post.update_success']);
    }

    public function cropAndStorePhoto($file, $original_path = '')
    {
        $path = $file->hashName('posts');
        $image = Image::make($file);

        $image->fit(360, 360);

        Storage::disk('public')->delete($original_path);
        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
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

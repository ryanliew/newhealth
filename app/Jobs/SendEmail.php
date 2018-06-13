<?php

namespace App\Jobs;

use App\Mail\Newsletter;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post, $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locale = $this->user->country_id == 48 ? 'zh' : 'en';
        $email = new Newsletter($this->user, $this->post, $locale);
        Mail::to($this->user->email)->queue($email);
    }
}

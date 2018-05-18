<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class RegisterSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $locale;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $locale)
    {
        $this->user = $user;
        $this->locale = $locale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->locale);
        return $this->view('vendor.notifications.email');
    }
}

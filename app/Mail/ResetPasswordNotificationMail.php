<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ResetPasswordNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $locale, $user, $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $locale, $token)
    {
        $this->locale = $locale;
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->locale);
        return $this->view('vendor.notifications.reset-password');
    }
}

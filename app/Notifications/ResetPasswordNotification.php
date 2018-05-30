<?php

namespace App\Notifications;

use App\Mail\ResetPasswordNotificationMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected $user, $locale, $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $locale = 'en', $token)
    {
        $this->locale = $locale;
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        App::setLocale($this->locale);
        return (new ResetPasswordNotificationMail($this->user, $this->locale, $this->token))
                ->onQueue('default')
                ->to($this->user->email)
                ->subject( __('mail.reset_password') );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

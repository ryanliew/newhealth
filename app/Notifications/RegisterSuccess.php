<?php

namespace App\Notifications;

use App\Mail\RegisterSuccess as RegisterSuccessMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class RegisterSuccess extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user, $locale;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $locale = 'en')
    {
        $this->user = $user;
        $this->locale = $locale;
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
        return (new RegisterSuccessMail($this->user, $this->locale))
               ->to($this->user->email)
               ->subject( __('mail.success_register') );
        // return (new MailMessage)
        //             ->markdown('vendor.notifications.email', ['user' => $this->user])
        //             ->subject( __('mail.success_register') );
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

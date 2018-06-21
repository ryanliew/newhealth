<?php

namespace App\Notifications;

use App\Mail\IdentityVerificationDocumentsRejectedMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class IdentityVerificationDocumentsRejectedNotification extends Notification
{
    use Queueable;

    protected $locale, $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $locale = 'en')
    {
        $this->locale = $locale;
        $this->user = $user;
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
        return (new IdentityVerificationDocumentsRejectedMail($this->user, $this->locale))
                ->onQueue('default')
                ->to($this->user->email)
                ->subject( __('mail.document_rejected') );
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

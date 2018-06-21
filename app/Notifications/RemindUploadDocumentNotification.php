<?php

namespace App\Notifications;

use App\Mail\RemindUploadDocumentMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class RemindUploadDocumentNotification extends Notification
{
    use Queueable;
    protected $user, $locale;
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
        return (new RemindUploadDocumentMail($this->user, $this->locale))
                ->onQueue('default')
                ->to($this->user->email)
                ->subject( __('mail.document_required') );
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

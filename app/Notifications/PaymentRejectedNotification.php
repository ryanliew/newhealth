<?php

namespace App\Notifications;

use App\Mail\PaymentRejectedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class PaymentRejectedNotification extends Notification
{
    use Queueable;

    protected $purchase, $locale, $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($purchase, $locale = 'en', $user)
    {
        $this->purchase = $purchase;
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
        return (new PaymentRejectedMail($this->user, $this->locale, $this->purchase))
                ->onQueue('default')
                ->to($this->user->email)
                ->subject( __('mail.payment_rejected') );
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

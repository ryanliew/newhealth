<?php

namespace App\Notifications;

use App\Mail\PurchaseCompleteMail;
use App\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class PurchaseCompleteNotification extends Notification
{
    use Queueable;

    private $purchase;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
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
        $locale = $this->purchase->user->country_id == 48 ? 'zh' : 'en';
        App::setLocale($locale);
        return (new PurchaseCompleteMail($this->purchase, $locale))
                    ->onQueue('default')
                    ->to($this->purchase->user->email)
                    ->subject( __('mail.purchase_verified') );
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

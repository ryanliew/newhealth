<?php

namespace App\Notifications;

use App\Mail\RedemptionCreateMail;
use App\Redemption;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;

class RedemptionCreateNotification extends Notification
{
    use Queueable;

    protected $redemption;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Redemption $redemption)
    {
        $this->redemption = $redemption;
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
        $locale = $this->redemption->user->country_id == 48 ? 'zh' : 'en';
        App::setLocale($locale);
        return (new RedemptionCreateMail($this->redemption, $locale))
                    ->onQueue('default')
                    ->to($this->redemption->user->email)
                    ->subject( __('mail.redemption_request') );
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

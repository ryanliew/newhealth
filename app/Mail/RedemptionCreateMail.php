<?php

namespace App\Mail;

use App\Redemption;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class RedemptionCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $locale, $redemption;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Redemption $redemption, $locale)
    {
        $this->locale = $locale;
        $this->redemption = $redemption;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->locale);
        return $this->view('vendor.notifications.redemption-created');
    }
}

<?php

namespace App\Mail;

use App\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class PurchaseCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $locale, $purchase;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Purchase $purchase, $locale)
    {
        $this->locale = $locale;
        $this->purchase = $purchase;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->locale);
        return $this->view('vendor.notifications.purchase-verified');
    }
}

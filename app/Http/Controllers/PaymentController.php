<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Purchase;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Purchase $purchase)
    {
        $messages = [
            'payment_slip_path.required' => 'payment.invalid_payment_slip',
            'payment_slip_path.file' => 'payment.invalid_payment_slip',
            'payment_slip_path.max' => 'payment.payment_slip_exceed_size',
        ];

        $this->validate(request(), [
            'payment_slip_path' => ['required', 'file', 'max:8000']
        ], $messages);

    	$payment = Payment::create(['amount' => request()->amount,
    								'user_id' => request()->user_id,
    								'payment_slip_path' => request()->file('payment_slip_path')->store('payments', 'public')
    								]);

    	$purchase->update(['payment_id' => $payment->id, 'status' => 'pending_verification']);

    	return json_encode(['message' => 'payment.success', 'payment' => $payment]);
    }

    public function verify(Payment $payment)
    {
        $payment->update(['is_verified' => true]);

        $payment->purchase->verify();

        return json_encode(['message' => 'payment.verify_success', 'payment' => $payment]);
    }
}

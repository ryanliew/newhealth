<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Purchase;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                                    'amount_std' => request()->amount_std,
                                    'is_std' => User::find(request()->user_id)->country_id == 48,
    								'user_id' => request()->user_id,
    								'payment_slip_path' => request()->file('payment_slip_path')->store('payments', 'public')
    								]);

    	$purchase->update(['payment_id' => $payment->id, 'status' => 'pending_verification']);

    	return json_encode(['message' => 'payment.success', 'payment' => $payment]);
    }

    public function revise(Payment $payment)
    {
        $messages = [
            'payment_slip_path.required' => 'payment.invalid_payment_slip',
            'payment_slip_path.file' => 'payment.invalid_payment_slip',
            'payment_slip_path.max' => 'payment.payment_slip_exceed_size',
        ];

        $this->validate(request(), [
            'payment_slip_path' => ['required', 'file', 'max:8000']
        ], $messages);

        Storage::disk('public')->delete($payment->payment_slip_path);

        $payment->update(['payment_slip_path' => request()->file('payment_slip_path')->store('payments', 'public')]);

        $payment->purchase()->update(['status' => 'pending_verification']);

        return json_encode(['message' => 'payment.success', 'payment' => $payment]);
    }

    public function verify(Payment $payment)
    {
        $payment->update(['is_verified' => true]);

        $payment->purchase->verify();

        return json_encode(['message' => 'payment.verify_success', 'payment' => $payment]);
    }

    public function reject(Payment $payment)
    {
        $this->validate(request(), [
            'reject_note' => 'required'
        ]);

        $payment->update(['is_verified' => false, 'reject_note' => request()->reject_note]);

        $payment->purchase->reject(request()->reject_note);

        return json_encode(['message' => 'payment.reject_success', 'payment' => $payment]);
    }
}

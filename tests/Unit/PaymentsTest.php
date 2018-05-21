<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admins_can_verify_payment()
    {
        $this->signIn();

    	$payment = create('App\Payment');

        $this->post('/api/payment/verify/' . $payment->id);

        $this->assertDatabaseHas('payments', ['is_verified' => true]);
    } 

    /** @test */
    public function members_can_submit_payment()
    {
    	$this->signIn();

        Storage::fake('public');

        $purchase = create('App\Purchase');

    	$payment = factory('App\Payment')->states('file')->make(['payment_slip_path' => $file = UploadedFile::fake()->image('avatar.jpg')]);

    	$this->json('POST', '/api/purchase/pay/' . $purchase->id, $payment->toArray());

        $this->assertDatabaseHas('payments', ['is_verified' => false, 'payment_slip_path' => 'payments/' . $file->hashName() ]);

        Storage::disk('public')->assertExists('payments/' . $file->hashName());

        return $payment;
    }
}	
<?php

namespace Tests\Feature;

use App\Purchase;
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
        $user = create('App\User', ['tree_count' => 2]);
        $this->signIn($user);
        
        $purchase = make('App\Purchase');

        $package1 = create('App\Package');
        $package2 = create('App\Package');

        $total_amount = ( $package1->price * 2 ) + $package2->price + .0;

        $total_trees = ( $package1->tree_count * 2 ) + $package2->tree_count + 2;

        $this->post('/api/purchases', [ "user_id" => auth()->user()->id, 
                                        "packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"8000.00"},{"amount":1,"id":' . $package2->id . ',"price":"24000.00"}]'
                                                    ]);
        $purchase = Purchase::first();

    	$payment = create('App\Payment');

        $purchase->update(['payment_id' => $payment->id]);

        $this->post('/api/payment/verify/' . $payment->id);

        $this->assertDatabaseHas('payments', ['is_verified' => true]);
        $this->assertDatabaseHas('purchases', ['is_verified' => true, 'id' => $purchase->id]);
        $this->assertDatabaseHas('users', ['tree_count' => $total_trees]);
    } 

    /** @test */
    public function members_can_submit_payment()
    {
    	$this->signIn();

        Storage::fake('public');

        $purchase = create('App\Purchase');

    	$payment = factory('App\Payment')->make(['payment_slip_path' => $file = UploadedFile::fake()->image('avatar.jpg')]);

    	$this->json('POST', '/api/purchase/pay/' . $purchase->id, $payment->toArray());

        $this->assertDatabaseHas('payments', ['is_verified' => false, 'payment_slip_path' => 'payments/' . $file->hashName() ]);

        Storage::disk('public')->assertExists('payments/' . $file->hashName());

        return $payment;
    }
}	
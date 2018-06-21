<?php

namespace Tests\Unit;

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
                                        "packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"8000.00", "price_std":"2000.00"},{"amount":1,"id":' . $package2->id . ',"price":"24000.00", "price_std":"2000.00"}]'
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

    /** @test */
    public function parents_will_receive_roll_up_commision_once_payment_is_verified()
    {
        // We have a child and parent relationship
        $grandparent = create('App\User', ['user_level' => 4]);
        $parent = create('App\User', ['user_level' => 1]);
        $child = create('App\User');

        $parent->appendToNode($grandparent)->save();
        $child->appendToNode($parent)->save();  

        $this->signIn();
        
        // We have 2 packages
        $purchase = make('App\Purchase');

        $package1 = create('App\Package', ['price' => 1000, 'price_std' => 500]);
        $package2 = create('App\Package', ['price' => 2000, 'price_std' => 1000]);

        //dd($parent->fresh()->commision_percentage);
        $parent_amount = (( $package1->price * 2 ) + $package2->price ) * 0.08;
        $grandparent_amount = (( $package1->price * 2 ) + $package2->price ) * 0.12;
        $parent_amount_std = (( $package1->price_std * 2 ) + $package2->price_std ) * 0.08;
        $grandparent_amount_std = (( $package1->price_std * 2 ) + $package2->price_std ) * 0.12;
        // We have a purchase order with payment
        $this->post('/api/purchases', [ "user_id" => $child->id, "purchase_date" => '2018-06-12',
                                        "packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"1000.00", "price_std": "500.00"},{"amount":1,"id":' . $package2->id . ',"price":"2000.00", "price_std": "1000"}]'
                                                    ]);
        $purchase = Purchase::first();

        $payment = create('App\Payment');

        $purchase->update(['payment_id' => $payment->id]);

        // Admin verify the payment
        $this->post('/api/payment/verify/' . $payment->id);
 
        $this->assertDatabaseHas('transactions', ['user_id' => $parent->id, 'amount' => $parent_amount . ".0", 'amount_std' => $parent_amount_std . ".0", 'description' => 'tree_purchase', 'type' => 'one_time_commision', 'is_std' => $purchase->is_std, "target_id" => $child->id, "purchase_id" => $purchase->id, 'date' => $purchase->created_at]);

        $this->assertDatabaseHas('transactions', ['user_id' => $grandparent->id, 'amount' => $grandparent_amount . ".0", 'amount_std' => $grandparent_amount_std . ".0", 'description' => 'tree_purchase', 'type' => 'one_time_commision', 'is_std' => $purchase->is_std, "target_id" => $child->id, "purchase_id" => $purchase->id, 'date' => $purchase->created_at]);
        
    } 

    /** @test */
    public function admins_can_reject_payment()
    {
        $user = create('App\User', ['tree_count' => 2]);
        $this->signIn($user);
        
        $purchase = make('App\Purchase');

        $package1 = create('App\Package');
        $package2 = create('App\Package');

        $total_amount = ( $package1->price * 2 ) + $package2->price + .0;

        $total_trees = ( $package1->tree_count * 2 ) + $package2->tree_count + 2;

        $this->post('/api/purchases', [ "user_id" => auth()->user()->id, 
                                        "packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"8000.00", "price_std":"2000.00"},{"amount":1,"id":' . $package2->id . ',"price":"24000.00", "price_std":"2000.00"}]'
                                                    ]);
        $purchase = Purchase::first();

        $payment = create('App\Payment');

        $purchase->update(['payment_id' => $payment->id]);

        $this->post('/api/payment/reject/' . $payment->id, ["reject_note" => "Some reject notes here"]);

        $this->assertDatabaseHas('payments', ['is_verified' => false, 'reject_note' => "Some reject notes here"]);

        $this->assertDatabaseHas('purchases', ['is_verified' => false, 'status' => "rejected"]);
    } 
}	
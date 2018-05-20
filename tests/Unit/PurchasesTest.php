<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchasesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_make_purchases()
    {
    	$this->signIn();

 		$purchase = make('App\Purchase');

 		$package1 = create('App\Package');
 		$package2 = create('App\Package');

 		$total_amount = ( $package1->price * 2 ) + $package2->price + .0;

 		$this->post('/api/purchases', [ "user_id" => auth()->user()->id, 
 										"packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"8000.00"},{"amount":1,"id":' . $package2->id . ',"price":"24000.00"}]'
 													]);

 		$this->assertDatabaseHas('purchases', ['total_price' => sprintf("%.1f", $total_amount)]);
    } 

    /** @test */
    public function admin_can_verify_purchase()
    {
    	$this->signIn();

    	$purchase = create('App\Purchase');

    	$this->post('/api/purchase/verify/' . $purchase->id, ['is_verified' => true]);

    	$this->assertDatabaseHas('purchases', ['id' => $purchase->id, 'is_verified' => true]);
    }

    /** @test */
    public function purchases_can_be_retrieved()
    {
    	$user = create('App\User');

    	$this->signIn($user);

    	$purchase = create('App\Purchase', ['user_id' => $user->id]);

    	$response = $this->get('/api/user/' . $user->id . '/purchases')->json();

    	$this->assertCount(1, $response['data']);
    }  
}
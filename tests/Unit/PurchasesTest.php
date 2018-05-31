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

 		$package1 = create('App\Package', ["price" => 8000]);
 		$package2 = create('App\Package', ["price" => 24000]);

 		$total_amount = ( $package1->price * 2 ) + $package2->price + .0;

 		$this->post('/api/purchases', [ "user_id" => auth()->user()->id, 
 										"packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"8000.00"},{"amount":"1","id":' . $package2->id . ',"price":"24000.00"}]'
 													]);

 		$this->assertDatabaseHas('purchases', ['total_price' => sprintf("%.1f", $total_amount)]);
    }

    /** @test */
    public function other_user_will_get_other_purchases()
    {
        $user = create('App\User', ["country_id" => 48]); // Country that is not MY or SG

        $this->signIn($user);

        $purchase = make('App\Purchase');

        $package1 = create('App\Package', ["price" => 8000, "price_std" => 3000]);
        $package2 = create('App\Package', ["price" => 24000, "price_std" => 15000]);

        $total_amount = ( $package1->price_std * 2 ) + $package2->price_std;

        $this->post('/api/purchases', [ "user_id" => auth()->user()->id, 
                                        "packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"8000.00", "price_std":"3000"},{"amount":"1","id":' . $package2->id . ',"price":"24000.00","price_std":"15000"}]'
                                                    ]);
        $this->assertDatabaseHas('purchases', ['is_std' => true, 'total_price_std' => sprintf("%.1f", $total_amount)]);
    } 

    /** @test */
    public function local_user_will_get_local_purchases()
    {
        $user = create('App\User', ["country_id" => 162]); // Country that is not MY or SG

        $this->signIn($user);

        $purchase = make('App\Purchase');

        $package1 = create('App\Package', ["price" => 8000, "price_std" => 3000]);
        $package2 = create('App\Package', ["price" => 24000, "price_std" => 15000]);

        $total_amount = ( $package1->price_std * 2 ) + $package2->price_std;

        $this->post('/api/purchases', [ "user_id" => auth()->user()->id, 
                                        "packages" => '[{"amount":"2","id":' . $package1->id . ',"price":"8000.00", "price_std":"3000"},{"amount":"1","id":' . $package2->id . ',"price":"24000.00","price_std":"15000"}]'
                                                    ]);
        $this->assertDatabaseHas('purchases', ['is_std' => false, 'total_price_std' => sprintf("%.1f", $total_amount)]);
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

    	$response = $this->getJson('/api/user/' . $user->id . '/purchases')->json();

    	$this->assertCount(1, $response['data']);
    }
    
}
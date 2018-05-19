<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_submit_payment()
    {
    		
    }

    /** @test */
    public function members_can_submit_payment()
    {
    	$this->submitPayment([]);
    }  

    /** @test */
    public function admins_can_verify_payment()
    {
    	
    } 

    protected function submitPayment($overrides = [])
    {
    	$this->withExceptionHandling()->signIn();

    	$payment = make('App\Payment', $overrides);

    	return $this->post(route('payments'), $payment->toArray());
    }
}	
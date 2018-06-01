<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TransactionTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function admin_can_change_transaction_payout_status_to_paid()
    {
		$this->signIn();

		$user = create('App\User');

		$date = Carbon::now();
		$next_month = $date->copy()->addMonth();
		create('App\Transaction', ['user_id' => $user->id, 'date' => $date, 'is_std' => true], 2);

		create('App\Transaction', ['user_id' => $user->id, 'date' => $date, 'is_std' => false], 1);

		create('App\Transaction', ['user_id' => 4, 'date' => $date], 2);

		create('App\Transaction', ['user_id' => $user->id, 'date' => $next_month], 1);

		$response = $this->post('/api/admin/transaction/paid', ['user_id' => $user->id, 'month' => $date, 'is_std' => true]);

		$response->assertStatus(200)->assertJson(['message' => 'transaction.status_updated']);

		$this->assertDatabaseHas('transactions', ["user_id" => $user->id, "payout_status" => "paid", "date" => $date->toDateTimeString(), "is_std" => true]);
		$this->assertDatabaseHas('transactions', ["user_id" => $user->id, "payout_status" => "pending", "date" => $date->toDateTimeString(), "is_std" => false]);
		$this->assertDatabaseHas('transactions', ["user_id" => 4, "payout_status" => "pending"]);
		$this->assertDatabaseHas('transactions', ["user_id" => $user->id, "payout_status" => "pending", "date" => $next_month->toDateTimeString()]);
    } 
}

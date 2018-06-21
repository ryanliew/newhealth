<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admins_can_reject_KYC_document()
    {
    	$this->signIn();

    	$user = create("App\User");

    	$this->post('/api/user/' . $user->id . '/kyc/reject/', ['reject_note' => "Test reject note"]);

    	$this->assertDatabaseHas('users', ['id_status' => 'rejected', 'reject_note' => 'Test reject note']);
    } 
}
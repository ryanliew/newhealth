<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PackageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admins_can_create_package()
    {
    	$this->signIn();

    	$package = make('App\Package');

    	$response = $this->json('POST', 'api/admin/packages', $package->toArray());

    	$response->assertStatus(200)->assertJson(['message' => 'package.create_success']);

    	$this->assertDatabaseHas('packages', ['tree_count' => $package->tree_count]);
    } 

    /** @test */
    public function admins_can_update_package()
    {
    	$this->signIn();

    	$package = create('App\Package');

  		$package2 = make('App\Package', ['price_promotion' => 1000.00]);

  		$response = $this->json('POST', 'api/admin/packages/' . $package->id, $package2->toArray());

    	$response->assertStatus(200)->assertJson(['message' => 'package.update_success']);

    	$this->assertDatabaseHas('packages', ['price_promotion' => 1000.00]);
    }
}
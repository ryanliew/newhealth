<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Purchase::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
        'payment_id' => function() {
            return factory('App\Payment')->create()->id;
        },
        'status' => 'pending',
        'is_verified' => 'false'
    ];
});

$factory->state(App\Purchase::class, 'verified', function () {
	return [
		'is_verified' => true
	];
});

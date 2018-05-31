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

$factory->define(App\Package::class, function (Faker $faker) {
    $index = $faker->numberBetween(0, 3);
    $sizes = ['xs', 'sm', 'medium', 'large'];
    $treecount = [1, 3, 5, 10];

    return [
        'name' => $sizes[$index],
        'tree_count' => $treecount[$index],
        'price' => $treecount[$index] * 8000,
        'price_promotion' => $treecount[$index] * 6800,
        'price_std' => $treecount[$index] * 3000,
        'price_std_promotion' => $treecount[$index] * 1800
    ];
});

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

$factory->define(App\Models\Product::class, function (Faker $faker) {
    return [
//        'name' => $faker->realText(80),
        'price' => rand(1000, 7000),
        'original_price' => rand(500, 3500),
        'sku' => str_random(10),
        'file_id' => rand(4, 33),
        'stock' => rand(0, 1),
        'new' => rand(0, 1),
        'sale' => rand(0, 1),
    ];
});

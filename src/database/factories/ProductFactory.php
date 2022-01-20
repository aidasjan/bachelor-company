<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'code' => Str::random(6),
        'name' => Str::random(10),
        'price' => random_int(0, 100),
        'currency' => 'EUR',
        'unit' => 'unit',
        'subcategory_id' => 1
    ];
});

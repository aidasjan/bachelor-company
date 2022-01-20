<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Subcategory;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Subcategory::class, function (Faker $faker) {
    return [
        'code' => Str::random(6),
        'name' => Str::random(10),
        'name_ru' => Str::random(10),
        'discount' => random_int(0, 100),
        'category_id' => 1
    ];
});

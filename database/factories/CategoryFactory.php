<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'code' => Str::random(6),
        'name' => Str::random(10),
        'name_ru' => Str::random(10)
    ];
});

<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => Str::random(6),
            'name' => Str::random(10),
            'price' => random_int(0, 100),
            'currency' => 'EUR',
            'unit' => 'unit',
            'category_id' => 1
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => Str::random(6),
            'name' => Str::random(10),
            'price' => random_int(1, 100),
            'currency' => 'EUR',
            'unit' => 'unit',
            'discount' => random_int(0, 10),
            'order_id' => 1,
            'quantity' => random_int(0, 10)
        ];
    }
}

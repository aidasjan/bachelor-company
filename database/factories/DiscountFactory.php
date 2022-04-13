<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    public function definition()
    {
        return [
            'discount' => random_int(0, 50),
        ];
    }
}

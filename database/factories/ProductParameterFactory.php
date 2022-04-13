<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductParameterFactory extends Factory
{
    public function definition()
    {
        return [
            'value' => random_int(1, 10),
        ];
    }
}

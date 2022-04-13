<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParameterFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => Str::random(),
        ];
    }
}

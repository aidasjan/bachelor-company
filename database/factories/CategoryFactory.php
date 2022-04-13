<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => Str::random(6),
            'name' => Str::random(10),
            'name_ru' => Str::random(10),
            'discount' => random_int(0, 10),
        ];
    }
}

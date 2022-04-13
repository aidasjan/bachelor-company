<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'code' => Str::random(10),
            'webpage_url' => $this->faker->url(),
            'portal_url' => $this->faker->url(),
            'address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'is_disabled' => 0
        ];
    }
}

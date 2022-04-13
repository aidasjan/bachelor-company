<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail;
        return [
            'name' => encrypt($this->faker->name),
            'email' => encrypt($email),
            'role' => encrypt('client'),
            'email_h' => hash('sha1', $email),
            'email_verified_at' => now(),
            'password' => '',
            'remember_token' => Str::random(10),
            'is_new' => 0,
        ];
    }
}

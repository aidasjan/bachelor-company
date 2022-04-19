<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => Str::random(),
            'type' => 'image',
            'file_name' => Str::random(),
            'file_extension' => 'pdf',
            'file_mime_type' => 'pdf'
        ];
    }
}

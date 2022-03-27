<?php

namespace App\Services;

use App\Models\Usage;

class UsageService
{
    public function all()
    {
        return Usage::all();
    }

    public function find($id)
    {
        return Usage::find($id);
    }
}

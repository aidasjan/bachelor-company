<?php

namespace Tests;

use App\Models\Company;
use App\Models\User;

class TestUtils
{
    public static function setupAdmin()
    {
        Company::factory()->create([ 'id' => 1 ]);
        $admin = User::factory()->create([ 'company_id' => 1, 'role' => encrypt('admin') ]);
        return $admin;
    }
}

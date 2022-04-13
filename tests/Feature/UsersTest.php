<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_logged_in_with_valid_token()
    {
        User::factory()->create(['id' => '1', 'access_token' => 'validtoken']);

        $response = $this->get('login/1/validtoken');

        $response->assertStatus(302);
    }

    public function test_user_is_not_logged_in_without_valid_token()
    {
        User::factory()->create(['id' => '1', 'access_token' => 'validtoken']);

        $response = $this->get('login/1/invalidtoken');

        $response->assertStatus(401);
    }
    
    public function test_users_are_displayed()
    {
        $admin = TestUtils::setupAdmin();
        User::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get('/users');
        
        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(6, $data['users']);
    }

    public function test_new_user_is_saved()
    {
        $admin = TestUtils::setupAdmin();
        $payload = [
            'name' => 'Name',
            'email' => 'user@test.local',
        ];

        $response = $this->actingAs($admin)->post('/register', $payload);

        $response->assertStatus(302);
        $user = User::find(2);
        $this->assertEquals('Name', $user->name);
    }
    
}

<?php

namespace Tests\Feature;

use App\Models\Usage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class UsagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_usages_are_displayed()
    {
        $testRecords = Usage::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/usages');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['usages']);
        $this->assertEquals($data['usages'][0]->name, $testRecords[0]->name);
    }

    public function test_new_usage_is_saved()
    {
        $payload = ['name' => 'Usage'];

        $response = $this->actingAs(TestUtils::setupAdmin())->post('/usages', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('usages', ['name' => 'Usage']);
    }

    public function test_usage_is_updated()
    {
        Usage::factory()->create(['id' => 1]);
        $payload = ['name' => 'New Usage'];

        $response = $this->actingAs(TestUtils::setupAdmin())->put('/usages/1', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('usages', ['name' => 'New Usage']);
    }
    
    public function test_update_not_found_if_usage_does_not_exist()
    {
        Usage::factory()->create(['id' => 1]);
        $payload = ['name' => 'New Usage'];

        $response = $this->actingAs(TestUtils::setupAdmin())->put('/usages/2', $payload);

        $response->assertStatus(404);
    }

    public function test_usage_is_destroyed()
    {
        Usage::factory()->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->delete('/usages/1');

        $response->assertStatus(302);
        $this->assertDatabaseCount('usages', 0);
    }

    public function test_edit_has_usage_data()
    {
        $usage = Usage::factory()->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/usages/1/edit');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertEquals($usage->name, $data['usage']->name);
    }
}

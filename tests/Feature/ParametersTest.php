<?php

namespace Tests\Feature;

use App\Models\Parameter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class ParametersTest extends TestCase
{
    use RefreshDatabase;

    public function test_parameters_are_displayed()
    {
        $testRecords = Parameter::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/parameters');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['parameters']);
        $this->assertEquals($data['parameters'][0]->name, $testRecords[0]->name);
    }

    public function test_new_parameter_is_saved()
    {
        $payload = ['name' => 'Parameter'];

        $response = $this->actingAs(TestUtils::setupAdmin())->post('/parameters', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('parameters', ['name' => 'Parameter']);
    }

    public function test_parameter_is_updated()
    {
        Parameter::factory()->create(['id' => 1]);
        $payload = ['name' => 'New Parameter'];

        $response = $this->actingAs(TestUtils::setupAdmin())->put('/parameters/1', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('parameters', ['name' => 'New Parameter']);
    }
    
    public function test_update_not_found_if_parameter_does_not_exist()
    {
        Parameter::factory()->create(['id' => 1]);
        $payload = ['name' => 'New Parameter'];

        $response = $this->actingAs(TestUtils::setupAdmin())->put('/parameters/2', $payload);

        $response->assertStatus(404);
    }

    public function test_parameter_is_destroyed()
    {
        Parameter::factory()->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->delete('/parameters/1');

        $response->assertStatus(302);
        $this->assertDatabaseCount('parameters', 0);
    }

    public function test_edit_has_parameter_data()
    {
        $parameter = Parameter::factory()->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/parameters/1/edit');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertEquals($parameter->name, $data['parameter']->name);
    }
}

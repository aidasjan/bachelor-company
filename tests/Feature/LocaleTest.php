<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_locale_is_changed()
    {
        $response = $this->get('/language/en');

        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'en');
    }

    public function test_locale_is_not_changed_if_language_is_not_supported()
    {
        $response = $this->get('/language/cn');
        $response->assertStatus(404);
    }
}

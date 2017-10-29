<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelpTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function HelpController_showHelp_method_returns_the_about_view()
    {
        $response = $this->get('/help');

        $response
            ->assertSuccessful()
            ->assertViewIs('help')
            ->assertSee('How to Use Tasktic');
    }
}

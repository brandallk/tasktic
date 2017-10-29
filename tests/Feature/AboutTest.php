<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function AboutController_showAbout_method_returns_the_about_view()
    {
        $response = $this->get('/about');

        $response
            ->assertSuccessful()
            ->assertViewIs('about')
            ->assertSee('About Tasktic');
    }
}

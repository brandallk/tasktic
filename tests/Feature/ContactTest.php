<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ContactController_showContact_method_returns_the_contact_view()
    {
        $response = $this->get('/contact');

        $response
            ->assertSuccessful()
            ->assertViewIs('contact')
            ->assertSee('Contact Me');
    }
}

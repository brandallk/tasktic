<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WelcomeTest extends TestCase
{
    use RefreshDatabase;

    protected function registerNewUser()
    {
        $userDetails = [
            'name' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        
        $response = $this->post('/register', $userDetails);

        return User::where('name', 'newuser')->first();
    }

    /** @test */
    public function showWelcome_method_returns_the_welcome_view()
    {
        $response = $this->get('/');

        $response
            ->assertSuccessful()
            ->assertViewIs('welcome')
            ->assertSee('Welcome to Tasktic!');
    }

    /** @test */
    public function an_unregistered_user_sees_login_and_register_buttons_in_welcome_view()
    {
        $response = $this->get('/');

        $response
            ->assertSee('Log In')
            ->assertSee('Register')
            ->assertDontSee('Home');
    }

    /** @test */
    public function a_logged_in_registered_user_sees_home_button_in_welcome_view()
    {
        $user = $this->registerNewUser();

        $response = $this->get('/');

        $response
            ->assertSee('Home')
            ->assertDontSee('Log In')
            ->assertDontSee('Register');
    }

    /** @test */
    public function a_logged_out_registered_user_sees_login_and_register_buttons_in_welcome_view()
    {
        $user = $this->registerNewUser();
        Auth::logout();

        $response = $this->get('/');

        $this->assertFalse(Auth::check());
        
        $response
            ->assertSee('Log In')
            ->assertSee('Register')
            ->assertDontSee('Home');
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeTest extends TestCase
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
    public function HomeController_showHome_method_returns_the_admin_dashboard_view_to_an_admin_user()
    {
        $adminUser = $this->registerNewUser();
        $adminUser->role = 'admin';
        $adminUser->save();

        $response = $this->actingAs($adminUser)
                         ->get('/home');

        $this->assertEquals('admin', Auth::user()->role);

        $response
            ->assertSuccessful()
            ->assertViewIs('admin.dashboard')
            ->assertSee('Tasktic Admin Dashboard');
    }

    /** @test */
    public function HomeController_showHome_method_redirects_a_nonadmin_user_to_lists_show_route()
    {
        $nonadminUser = $this->registerNewUser();

        $defaultList = $nonadminUser->getCurrentList();

        $response = $this->actingAs($nonadminUser)
                         ->get('/home');

        $this->assertEquals('visitor', Auth::user()->role);

        $response
            ->assertRedirect("/lists/{$defaultList->id}");
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ListTest extends TestCase
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
    public function ListController_index_method_returns_the_list_index_view()
    {
        $user = $this->registerNewUser();

        $response = $this->actingAs($user)
                         ->get('/lists');

        $response
            ->assertSuccessful()
            ->assertViewIs('list.index')
            ->assertSee('List Index');
    }
}

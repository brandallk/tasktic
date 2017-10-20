<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRegistration extends TestCase
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
    public function a_User_can_register()
    {
        $user = $this->registerNewUser();

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);
        $this->assertEquals(Auth::user()->name, 'newuser');
    }

    /** @test */
    public function a_new_User_gets_visitor_role_by_default()
    {
        $user = $this->registerNewUser();

        $this->assertEquals('visitor', $user->role);
    }

    /** @test */
    public function a_new_User_gets_a_default_TaskList()
    {
        $user = $this->registerNewUser();

        // A User's default TaskList is the only one that can have 'saved' == false
        $defaultList = $user->TaskLists->whereIn('saved', false)->first();

        $currentList = $user->getCurrentList();

        $this->assertEquals($defaultList->id, $currentList->id);
    }
}

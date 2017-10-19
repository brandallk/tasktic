<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;
use \App\Models\TaskList;

class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function User_can_register()
    {
        $userDetails = [
            'name' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        
        $response = $this->post('/register', $userDetails);

        $user = User::where('name', 'testuser')->first();

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);
        $this->assertEquals(Auth::user()->name, 'testuser');

        Auth::logout();
        // $user->delete();
    }

    /** @test */
    public function Users_current_list_is_the_Default_List_if_User_has_no_other_lists()
    {
        $userDetails = [
            'name' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        
        $response = $this->post('/register', $userDetails);

        $user = User::where('name', 'testuser')->first();

        $defaultList = $user->TaskLists->whereIn('saved', false)->first();

        $currentList = $user->getCurrentList();

        $this->assertEquals($defaultList->id, $currentList->id);

        Auth::logout();
        // $defaultList->delete();
        // $user->delete();
    }
}

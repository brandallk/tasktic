<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;
use \App\Models\TaskList;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_User_has_a_name_and_email()
    {
        $user = factory(User::class)->create(['name' => 'testuser', 'email' => 'testuser@example.com']);

        $this->assertEquals('testuser', $user->name);
        $this->assertEquals('testuser@example.com', $user->email);
    }

    /** 
      * @test
      *
      * Note: Can't use mass-assignment for user->role. Default role is 'visitor'.
      */
    public function a_User_has_a_role()
    {
        $user = factory(User::class)->create();
        $user->role = 'visitor';
        $user->save();

        $this->assertEquals('visitor', $user->role);
    }

    /** @test */
    public function a_User_can_be_assigned_admin_role()
    {
        $user = factory(User::class)->create();
        $user->role = 'admin';
        $user->save();

        $this->assertEquals('admin', $user->role);
    }
}

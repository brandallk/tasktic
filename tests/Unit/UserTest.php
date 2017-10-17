<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserTest extends TestCase
{
    public function testThatAUserCanLogin()
    {
        $user = User::create([
            'name' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password')
        ]);
        Auth::login($user);

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::user(), $user);
        $this->assertEquals(Auth::user()->name, 'testuser');

        $user->delete();
    }
}

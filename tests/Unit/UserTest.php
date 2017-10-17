<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;
use \App\Models\TaskList;

class UserTest extends TestCase
{
    public function testThatUserCanLogin()
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

        Auth::logout();
        $user->delete();
    }

    public function testThatUserCanGetItsLists()
    {
        $user = User::create([
            'name' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password')
        ]);
        Auth::login($user);

        $list1 = TaskList::create([
            'user_id' => Auth::id(),
            'name' => 'list1'
        ]);
        $list2 = TaskList::create([
            'user_id' => Auth::id(),
            'name' => 'list2'
        ]);

        $lists = $user->taskLists;

        $this->assertCount(2, $lists);
        $this->assertEquals($lists, TaskList::where('user_id', Auth::id())->get());

        Auth::logout();
        $list1->delete();
        $list2->delete();
        $user->delete();
    }
}

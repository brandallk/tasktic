<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;
use \App\Models\TaskList;

class UserTest extends TestCase
{
    /** @test */
    public function User_can_login()
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
        $user->delete();
    }

    /** @test */
    public function User_can_get_its_lists()
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

    /** @test */
    public function User_can_get_its_most_recently_loaded_list()
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

        $list1->last_time_loaded = (\Carbon\Carbon::today())->toDateTimeString();
        // Alternative using DateTime instead of Cabon:
        // $list1->last_time_loaded = (new \DateTime('today'))->format('Y-m-d H:i:s');
        $list1->save();

        $list2->last_time_loaded = (\Carbon\Carbon::yesterday())->toDateTimeString();
        // Alternative using DateTime instead of Cabon:
        // $list2->last_time_loaded = (new \DateTime('yesterday'))->format('Y-m-d H:i:s');
        $list2->save();

        $currentList = $user->getCurrentList();

        $this->assertEquals($currentList->id, $list1->id);

        Auth::logout();
        $list1->delete();
        $list2->delete();
        $user->delete();
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
        $defaultList->delete();
        $user->delete();
    }
}

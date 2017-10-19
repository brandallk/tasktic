<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;
use \App\Models\TaskList;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['name' => 'testuser']);
        Auth::login($this->user);
    }

    public function tearDown()
    {
        Auth::logout();
        
        parent::tearDown();
    }

    /** @test */
    public function User_can_login()
    {
        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::user(), $this->user);
        $this->assertEquals(Auth::user()->name, 'testuser');
    }

    /** @test */
    public function User_can_get_its_lists()
    {
        $list1 = TaskList::create([
            'user_id' => Auth::id(),
            'name' => 'list1'
        ]);

        $list2 = TaskList::create([
            'user_id' => Auth::id(),
            'name' => 'list2'
        ]);

        $lists = $this->user->taskLists;

        $this->assertCount(2, $lists);
        $this->assertEquals($lists, TaskList::where('user_id', Auth::id())->get());
    }

    /** @test */
    public function User_can_get_its_most_recently_loaded_list()
    {
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

        $currentList = $this->user->getCurrentList();

        $this->assertEquals($currentList->id, $list1->id);
    }
}

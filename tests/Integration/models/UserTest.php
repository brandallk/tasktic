<?php

namespace Tests\Integration\models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;
use \App\Models\TaskList;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');

        $this->user = factory(User::class)->create(['name' => 'testuser']);
        Auth::login($this->user);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');

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
        $list1 = factory(TaskList::class)->create(['user_id' => Auth::id()]);
        $list2 = factory(TaskList::class)->create(['user_id' => Auth::id()]);

        $lists = $this->user->taskLists;

        $this->assertCount(2, $lists);
        $this->assertEquals($lists, TaskList::where('user_id', Auth::id())->get());
    }

    /** @test */
    public function User_can_get_its_most_recently_loaded_list()
    {
        $mostRecentList = factory(TaskList::class)->create(['user_id' => Auth::id()]);
        $mostRecentList->last_time_loaded = (\Carbon\Carbon::today())->toDateTimeString();
        $mostRecentList->save();

        $anotherList = factory(TaskList::class)->create(['user_id' => Auth::id()]);
        $anotherList->last_time_loaded = (\Carbon\Carbon::yesterday())->toDateTimeString();
        $anotherList->save();

        // Note: A less-pretty alternative to creating the DateTimes using Cabon:
        // $list1->last_time_loaded = (new \DateTime('today'))->format('Y-m-d H:i:s');
        // $list2->last_time_loaded = (new \DateTime('yesterday'))->format('Y-m-d H:i:s');

        $this->assertEquals($this->user->getCurrentList(), $mostRecentList->id);
    }
}

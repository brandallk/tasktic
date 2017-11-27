<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;

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
      * Note: Can't use mass-assignment for user->role. Default role is 'visitor'. There can also be 'admin'
      * user(s).
      */
    public function a_User_has_a_role()
    {
        $user = factory(User::class)->create();
        $user->role = 'visitor';
        $user->save();

        $this->assertEquals('visitor', $user->role);
    }

    /** @test */
    public function a_User_can_add_and_access_TaskLists()
    {
        $user = factory(User::class)->create();

        $newList = factory(TaskList::class)->create(['user_id' => $user->id, 'name' => 'New List']);
        $user->taskLists()->save($newList);

        $usersNewList = $user->taskLists->where('name', 'New List')->first();

        $this->assertEquals($newList->id, $usersNewList->id);
    }

    /** 
      * @test
      *
      * Note: A less-pretty alternative to creating the DateTimes using Cabon:
      * $list1->last_time_loaded = (new \DateTime('today'))->format('Y-m-d H:i:s');
      * $list2->last_time_loaded = (new \DateTime('yesterday'))->format('Y-m-d H:i:s');
      */
    public function a_User_can_get_its_most_recently_loaded_TaskList()
    {
        $user = factory(User::class)->create();

        $mostRecentList = factory(TaskList::class)->create(['name' => 'Loaded Today', 'user_id' => $user->id]);
        $mostRecentList->last_time_loaded = (\Carbon\Carbon::today())->toDateTimeString();
        $mostRecentList->save();

        $anotherList = factory(TaskList::class)->create(['name' => 'Loaded Yesterday', 'user_id' => $user->id]);
        $anotherList->last_time_loaded = (\Carbon\Carbon::yesterday())->toDateTimeString();
        $anotherList->save();

        $this->assertEquals($user->getCurrentList()->name, 'Loaded Today');
    }

    /** @test */
    public function setTimezone_method_changes_the_users_timezone()
    {
        $user = factory(User::class)->create(['timezone' => 'UTC']);

        $OffsetFromUTCinMinutesAtDenver = -420;
        $user->setTimezone($OffsetFromUTCinMinutesAtDenver);

        $this->assertEquals('America/Denver', $user->timezone);

        $OffsetFromUTCinMinutesAtLosAngeles = -480;
        $user->setTimezone($OffsetFromUTCinMinutesAtLosAngeles);

        $this->assertEquals('America/Los_Angeles', $user->timezone);
    }
}

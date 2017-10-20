<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;

class TaskListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_TaskList_belongs_to_a_User()
    {
        $user = factory(User::class)->create(['name' => 'testuser']);

        $list = factory(TaskList::class)->create(['user_id' => $user->id]);
        $list->user()->associate($user);
        $list->save();

        $this->assertEquals($list->user->name, 'testuser');
    }
}

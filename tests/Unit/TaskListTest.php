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

        $this->assertEquals('testuser', $list->user->name);
    }

    /** @test */
    public function a_TaskList_has_a_name()
    {
        $list = factory(TaskList::class)->create(['name' => 'New List']);

        $this->assertEquals('New List', $list->name);
    }

    // /** @test */
    // public function a_TaskList_can_add_and_access_list_Categories()
    // {
        
    // }

    // /** @test */
    // public function a_new_TaskList_gets_true_autodelete_status_by_default()
    // {
        
    // }

    // /** @test */
    // public function a_default_TaskList_can_be_created()
    // {
        
    // }

    // /** @test */
    // public function a_new_default_TaskList_is_named_after_the_current_date()
    // {
        
    // }

    // /** @test */
    // public function a_new_default_TaskList_gets_false_saved_status()
    // {
        
    // }

    // /** @test */
    // public function a_new_default_TaskList_has_a_Category_named_none()
    // {
        
    // }

    // /** @test */
    // public function a_custom_TaskList_can_be_created()
    // {
        
    // }

    // /** @test */
    // public function a_new_custom_TaskList_gets_true_saved_status()
    // {
        
    // }

    // /** @test */
    // public function a_TaskList_can_add_and_access_list_elements()
    // {
        
    // }

    // /** @test */
    // public function a_list_element_can_be_deleted()
    // {
        
    // }
}

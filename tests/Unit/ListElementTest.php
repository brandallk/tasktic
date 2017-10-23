<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\ListElement;

class ListElementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_List_Element_can_be_created()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $listElement = ListElement::addListElement($list, 'task', 'Task Name', 'dnor794#dnu8v');
        
        $this->assertDatabaseHas('list_elements', ['task_list_id' => $list->id]);
    }

    // /** @test */
    // public function a_List_Element_has_a_type_a_name_and_a_unique_id()
    // {
    //     // given 
    //     // when 
    //     // then 
    // }

    // /** @test */
    // public function a_List_Element_belongs_to_a_TaskList()
    // {
    //     // given 
    //     // when 
    //     // then 
    // }

    // /** @test */
    // public function a_List_Element_can_be_deleted()
    // {
    //     // given 
    //     // when 
    //     // then 
    // }
}
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\DeadlineItem;

class DeadlineItemTest extends TestCase
{
    use RefreshDatabase;

    private function makeNewTask()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        return $task = Task::newTask($subcategory, 'Task Name');
    }

    /** @test */
    public function a_DeadlineItem_can_be_created()
    {
        $task = $this->makeNewTask();
        $item = DeadlineItem::newItem($task, 'deadline', '8:00 am, December 25, 2017');

        $this->assertDatabaseHas(
            'deadline_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'deadline' => '8:00 am, December 25, 2017']
        );
    }

    /** @test */
    public function a_new_DeadlineItem_is_added_to_its_parent_Tasks_taskItems_automatically()
    {
        $task = $this->makeNewTask();
        $item = DeadlineItem::newItem($task, 'deadline', '8:00 am, December 25, 2017');

        $this->assertDatabaseHas(
            'task_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'unique_id' => $item->list_element_id]
        );

        $this->assertCount(1, $task->taskItems()->get());
        $this->assertEquals(
            'deadline',
            $task->taskItems->where('unique_id', $item->list_element_id)->first()->type
        );
    }

    /** @test */
    public function a_new_DeadlineItem_is_added_to_its_parent_TaskLists_listElements_automatically()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name');
        $item = DeadlineItem::newItem($task, 'deadline', '8:00 am, December 25, 2017');

        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'deadline', 'name' => '8:00 am, December 25, 2017']
        );

        $this->assertEquals(
            'deadline',
            $list->listElements->where('unique_id', $item->list_element_id)->first()->type
        );

        $this->assertEquals(
            '8:00 am, December 25, 2017',
            $list->listElements->where('unique_id', $item->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_DeadlineItem_belongs_to_a_task()
    {
        $task = $this->makeNewTask();
        $item = DeadlineItem::newItem($task, 'deadline', '8:00 am, December 25, 2017');

        $this->assertEquals('8:00 am, December 25, 2017', $task->deadlineItem->deadline);
        
        // Note: the $task->deadline property is initially set only by method Task::newTask
        $this->assertEquals(null, $task->deadline);
    }

    /** @test */
    public function a_DeadlineItem_can_be_updated()
    {
        $task = $this->makeNewTask();
        $item = DeadlineItem::newItem($task, 'deadline', '8:00 am, December 25, 2017');

        $item->updateItem($task, '10:30 pm, November 3, 2012');

        // The DeadlineItem itself should be updated
        $this->assertDatabaseMissing(
            'deadline_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'deadline' => '8:00 am, December 25, 2017']
        );
        $this->assertDatabaseHas(
            'deadline_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'deadline' => '10:30 pm, November 3, 2012']
        );

        // The parent Task should be updated
        $this->assertEquals('10:30 pm, November 3, 2012', $task->deadlineItem->deadline);
        $this->assertEquals('10:30 pm, November 3, 2012', $task->deadline);

        // The parent TaskList's corresponding ListElement should be updated
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'deadline', 'name' => '8:00 am, December 25, 2017']
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'deadline', 'name' => '10:30 pm, November 3, 2012']
        );
    }

    /** @test */
    public function a_DeadlineItem_can_be_deleted()
    {
        $task = $this->makeNewTask();
        $item = DeadlineItem::newItem($task, 'deadline', '8:00 am, December 25, 2017');
        $uniqueID = $item->list_element_id;

        DeadlineItem::deleteItem($item, $task);

        // The DeadlineItem itself should be deleted
        $this->assertDatabaseMissing(
            'deadline_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'deadline' => '8:00 am, December 25, 2017']
        );

        // The parent Task should be updated
        $this->assertCount(0, $task->deadlineItem()->get());
        $this->assertEquals(null, $task->deadline);

        // The parent Task's corresponding TaskItem should be deleted
        $this->assertDatabaseMissing(
            'task_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'unique_id' => $uniqueID]
        );
        $this->assertCount(0, $task->taskItems()->get());

        // The parent TaskList's corresponding ListElement should be deleted
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'deadline', 'name' => '8:00 am, December 25, 2017']
        );
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\DetailItem;

class DetailItemTest extends TestCase
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
    public function a_DetailItem_can_be_created()
    {
        $task = $this->makeNewTask();
        $item = DetailItem::newItem($task, 'detail', 'a new task detail');

        $this->assertDatabaseHas(
            'detail_items',
            ['task_id' => $task->id, 'type' => 'detail', 'detail' => 'a new task detail']
        );
    }

    /** @test */
    public function a_new_DetailItem_is_added_to_its_parent_Tasks_taskItems_automatically()
    {
        $task = $this->makeNewTask();
        $item = DetailItem::newItem($task, 'detail', 'a new task detail');

        $this->assertDatabaseHas(
            'task_items',
            ['task_id' => $task->id, 'type' => 'detail', 'unique_id' => $item->list_element_id]
        );

        $this->assertCount(1, $task->taskItems()->get());
        $this->assertEquals(
            'detail',
            $task->taskItems->where('unique_id', $item->list_element_id)->first()->type
        );
    }

    /** @test */
    public function a_new_DetailItem_is_added_to_its_parent_TaskLists_listElements_automatically()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name');
        $item = DetailItem::newItem($task, 'detail', 'a new task detail');

        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'detail', 'name' => 'a new task detail']
        );

        $this->assertEquals(
            'detail',
            $list->listElements->where('unique_id', $item->list_element_id)->first()->type
        );

        $this->assertEquals(
            'a new task detail',
            $list->listElements->where('unique_id', $item->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_DetailItem_belongs_to_a_task()
    {
        $task = $this->makeNewTask();
        $item = DetailItem::newItem($task, 'detail', 'a new task detail');

        $this->assertEquals(
            'a new task detail',
            $task->detailItems->where('list_element_id', $item->list_element_id)->first()->detail
        );
    }

    /** @test */
    public function a_DetailItem_can_be_updated()
    {
        $task = $this->makeNewTask();
        $item = DetailItem::newItem($task, 'detail', 'original detail');

        $item->updateItem($task, 'a different detail');

        // The DetailItem itself should be updated
        $this->assertDatabaseMissing(
            'detail_items',
            ['task_id' => $task->id, 'type' => 'detail', 'detail' => 'original detail']
        );
        $this->assertDatabaseHas(
            'detail_items',
            ['task_id' => $task->id, 'type' => 'detail', 'detail' => 'a different detail']
        );

        // The parent Task should be updated
        $this->assertEquals(
            'a different detail',
            $task->detailItems->where('list_element_id', $item->list_element_id)->first()->detail
        );

        // The parent TaskList's corresponding ListElement should be updated
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'detail', 'name' => 'original detail']
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'detail', 'name' => 'a different detail']
        );
    }

    /** @test */
    public function a_DetailItem_can_be_deleted()
    {
        $task = $this->makeNewTask();
        $item = DetailItem::newItem($task, 'detail', 'a new task detail');
        $uniqueID = $item->list_element_id;

        DetailItem::deleteItem($item, $task);

        // The DetailItem itself should be deleted
        $this->assertDatabaseMissing(
            'detail_items',
            ['task_id' => $task->id, 'type' => 'detail', 'detail' => 'a new task detail']
        );

        // The parent Task should be updated
        $this->assertCount(0, $task->detailItems()->get());

        // The parent Task's corresponding TaskItem should be deleted
        $this->assertDatabaseMissing(
            'task_items',
            ['task_id' => $task->id, 'type' => 'detail', 'unique_id' => $uniqueID]
        );
        $this->assertCount(0, $task->taskItems()->get());

        // The parent TaskList's corresponding ListElement should be deleted
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'detail', 'name' => 'a new task detail']
        );
    }
}

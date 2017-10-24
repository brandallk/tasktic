<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\DetailItem;

class TaskItemTest extends TestCase
{
    use RefreshDatabase;

    private function makeNewTask()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');

        return $task = Task::newTask($subcategory, 'New Task');
    }

    private function makeNewDetailItem($task)
    {
        return $item = DetailItem::create([
            'task_id' => $task->id,
            'list_element_id' => uniqid(),
            'type' => 'detail',
            'detail' => 'new task detail'
        ]);
    }

    /** @test */
    public function a_TaskItem_can_be_created()
    {
        $task = $this->makeNewTask();
        $item = $this->makeNewDetailItem($task);
        $taskItem = TaskItem::addItem($item, $task);

        $this->assertDatabaseHas('task_items', ['task_id' => $task->id]);
    }

    /** @test */
    public function a_TaskItem_has_a_type_and_a_unique_id()
    {
        $task = $this->makeNewTask();
        $item = $this->makeNewDetailItem($task);
        $taskItem = TaskItem::addItem($item, $task);

        $this->assertEquals('detail', $taskItem->type);
        $this->assertEquals($item->list_element_id, $taskItem->unique_id);
    }

    /** @test */
    public function a_TaskItem_belongs_to_a_Task()
    {
        $task = $this->makeNewTask();
        $item = $this->makeNewDetailItem($task);
        $taskItem = TaskItem::addItem($item, $task);

        $this->assertEquals('New Task', $taskItem->task->name);
    }

    /** @test */
    public function a_TaskItem_can_be_deleted()
    {
        $task = $this->makeNewTask();
        $item = $this->makeNewDetailItem($task);
        $taskItem = TaskItem::addItem($item, $task);

        TaskItem::removeItem($item, $task);

        $this->assertDatabaseMissing(
            'task_items',
            ['id' => $taskItem->id, 'type' => 'detail']
        );
    }
}
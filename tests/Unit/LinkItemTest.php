<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\LinkItem;

class LinkItemTest extends TestCase
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
    public function a_LinkItem_can_be_created()
    {
        $task = $this->makeNewTask();
        $item = LinkItem::newItem($task, 'link', 'http://www.example.com');

        $this->assertDatabaseHas(
            'link_items',
            ['task_id' => $task->id, 'type' => 'link', 'link' => 'http://www.example.com']
        );
    }

    /** @test */
    public function a_new_LinkItem_is_added_to_its_parent_Tasks_taskItems_automatically()
    {
        $task = $this->makeNewTask();
        $item = LinkItem::newItem($task, 'link', 'http://www.example.com');

        $this->assertDatabaseHas(
            'task_items',
            ['task_id' => $task->id, 'type' => 'link', 'unique_id' => $item->list_element_id]
        );

        $this->assertCount(1, $task->taskItems()->get());
        $this->assertEquals(
            'link',
            $task->taskItems->where('unique_id', $item->list_element_id)->first()->type
        );
    }

    /** @test */
    public function a_new_LinkItem_is_added_to_its_parent_TaskLists_listElements_automatically()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name');
        $item = LinkItem::newItem($task, 'link', 'http://www.example.com');

        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'link', 'name' => 'http://www.example.com']
        );

        $this->assertEquals(
            'link',
            $list->listElements->where('unique_id', $item->list_element_id)->first()->type
        );

        $this->assertEquals(
            'http://www.example.com',
            $list->listElements->where('unique_id', $item->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_LinkItem_belongs_to_a_task()
    {
        $task = $this->makeNewTask();
        $item = LinkItem::newItem($task, 'link', 'http://www.example.com');

        $this->assertEquals(
            'http://www.example.com',
            $task->linkItems->where('list_element_id', $item->list_element_id)->first()->link
        );
    }

    /** @test */
    public function a_LinkItem_can_be_updated()
    {
        $task = $this->makeNewTask();
        $item = LinkItem::newItem($task, 'link', 'http://www.example.com');

        $item->updateItem($task, 'https://www.google.com');

        // The LinkItem itself should be updated
        $this->assertDatabaseMissing(
            'link_items',
            ['task_id' => $task->id, 'type' => 'link', 'link' => 'http://www.example.com']
        );
        $this->assertDatabaseHas(
            'link_items',
            ['task_id' => $task->id, 'type' => 'link', 'link' => 'https://www.google.com']
        );

        // The parent Task should be updated
        $this->assertEquals(
            'https://www.google.com',
            $task->linkItems->where('list_element_id', $item->list_element_id)->first()->link
        );

        // The parent TaskList's corresponding ListElement should be updated
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'link', 'name' => 'http://www.example.com']
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'link', 'name' => 'https://www.google.com']
        );
    }

    /** @test */
    public function a_LinkItem_can_be_deleted()
    {
        $task = $this->makeNewTask();
        $item = LinkItem::newItem($task, 'link', 'http://www.example.com');
        $uniqueID = $item->list_element_id;

        LinkItem::deleteItem($item, $task);

        // The LinkItem itself should be deleted
        $this->assertDatabaseMissing(
            'link_items',
            ['task_id' => $task->id, 'type' => 'link', 'link' => 'http://www.example.com']
        );

        // The parent Task should be updated
        $this->assertCount(0, $task->linkItems()->get());

        // The parent Task's corresponding TaskItem should be deleted
        $this->assertDatabaseMissing(
            'task_items',
            ['task_id' => $task->id, 'type' => 'link', 'unique_id' => $uniqueID]
        );
        $this->assertCount(0, $task->taskItems()->get());

        // The parent TaskList's corresponding ListElement should be deleted
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'link', 'name' => 'http://www.example.com']
        );
    }
}

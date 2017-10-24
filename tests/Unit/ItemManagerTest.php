<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\Managers\ItemManager;

class ItemManagerTest extends TestCase
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

    /** @test */
    public function ItemManager_can_create_a_new_deadline_item()
    {
        $task = $this->makeNewTask();

        ItemManager::newItem('deadline', 'tomorrow morning', $task);

        $this->assertDatabaseHas(
            'deadline_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'deadline' => 'tomorrow morning']
        );

        $this->assertDatabaseHas(
            'task_items',
            ['task_id' => $task->id, 'type' => 'deadline']
        );

        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'deadline', 'name' => 'tomorrow morning']
        );

        $this->assertEquals('tomorrow morning', $task->deadlineItem->deadline);
    }

    /** @test */
    public function ItemManager_can_create_a_new_detail_item()
    {
        $task = $this->makeNewTask();

        $item = ItemManager::newItem('detail', 'a new task detail', $task);

        $this->assertDatabaseHas(
            'detail_items',
            ['task_id' => $task->id, 'type' => 'detail', 'detail' => 'a new task detail']
        );

        $this->assertDatabaseHas(
            'task_items',
            ['task_id' => $task->id, 'type' => 'detail']
        );

        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'detail', 'name' => 'a new task detail']
        );

        $this->assertEquals(
            'a new task detail',
            $task->detailItems->where('list_element_id', $item->list_element_id)->first()->detail
        );
    }

    /** @test */
    public function ItemManager_can_create_a_new_link_item()
    {
        $task = $this->makeNewTask();

        $item = ItemManager::newItem('link', 'http://www.example.com', $task);

        $this->assertDatabaseHas(
            'link_items',
            ['task_id' => $task->id, 'type' => 'link', 'link' => 'http://www.example.com']
        );

        $this->assertDatabaseHas(
            'task_items',
            ['task_id' => $task->id, 'type' => 'link']
        );

        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'link', 'name' => 'http://www.example.com']
        );

        $this->assertEquals(
            'http://www.example.com',
            $task->linkItems->where('list_element_id', $item->list_element_id)->first()->link
        );
    }

    /** @test */
    public function ItemManager_can_delete_a_deadline_item()
    {
        $task = $this->makeNewTask();

        $item = ItemManager::newItem('deadline', 'tomorrow morning', $task);

        ItemManager::deleteItem('deadline', $item->list_element_id, $task);

        $this->assertDatabaseMissing(
            'deadline_items',
            ['task_id' => $task->id, 'type' => 'deadline', 'deadline' => 'tomorrow morning']
        );

        $this->assertDatabaseMissing(
            'task_items',
            ['task_id' => $task->id, 'type' => 'deadline']
        );

        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'deadline', 'name' => 'tomorrow morning']
        );

        $this->assertEquals(null, $task->deadlineItem);
    }

    /** @test */
    public function ItemManager_can_delete_a_detail_item()
    {
        $task = $this->makeNewTask();

        $item = ItemManager::newItem('detail', 'a new task detail', $task);

        $uniqueID = $item->list_element_id;

        ItemManager::deleteItem('detail', $uniqueID, $task);

        $this->assertDatabaseMissing(
            'detail_items',
            ['task_id' => $task->id, 'type' => 'detail', 'detail' => 'a new task detail']
        );

        $this->assertDatabaseMissing(
            'task_items',
            ['task_id' => $task->id, 'type' => 'detail']
        );

        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'detail', 'name' => 'a new task detail']
        );

        $this->assertEquals(
            null,
            $task->detailItems->where('list_element_id', $uniqueID)->first()
        );
    }

    /** @test */
    public function ItemManager_can_delete_a_link_item()
    {
        $task = $this->makeNewTask();

        $item = ItemManager::newItem('link', 'http://www.example.com', $task);

        $uniqueID = $item->list_element_id;

        ItemManager::deleteItem('link', $uniqueID, $task);

        $this->assertDatabaseMissing(
            'link_items',
            ['task_id' => $task->id, 'type' => 'link', 'link' => 'http://www.example.com']
        );

        $this->assertDatabaseMissing(
            'task_items',
            ['task_id' => $task->id, 'type' => 'link']
        );

        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'link', 'name' => 'http://www.example.com']
        );

        $this->assertEquals(
            null,
            $task->linkItems->where('list_element_id', $uniqueID)->first()
        );
    }
}

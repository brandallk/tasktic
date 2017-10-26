<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Managers\ListElementManager;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;

class ListElementManagerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ListElementManager_can_create_a_new_Category()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');

        $category = ListElementManager::newListElement('category', 'New Category', $list);

        // The new Category should be in the ListElements
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'category', 'name' => 'New Category']
        );

        // The new Category should be in the 'categories' table
        $this->assertDatabaseHas(
            'categories',
            ['task_list_id' => $list->id, 'name' => 'New Category']
        );

        // The 'null' Category that is created by default for each new TaskList should also be present
        $this->assertDatabaseHas(
            'categories',
            ['task_list_id' => $list->id, 'name' => null]
        );

        // The new Category + the default 'null' Category should equal 2 total Categories
        $this->assertCount(2, $list->categories()->get());
    }

    /** @test */
    public function ListElementManager_can_create_a_new_Subcategory()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::where('task_list_id', $list->id)->where('name', null)->first();

        $subcategory = ListElementManager::newListElement('subcategory', 'New Subcategory', $list);

        // The new Subcategory and its containing 'null' Category should be in the ListElements
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'subcategory', 'name' => 'New Subcategory']
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'category', 'name' => null]
        );

        // The new Subcategory and its containing 'null' Category should be in their respective db tables
        $this->assertDatabaseHas(
            'subcategories',
            ['category_id' => $category->id, 'name' => 'New Subcategory']
        );
        $this->assertDatabaseHas(
            'categories',
            ['task_list_id' => $list->id, 'name' => null]
        );

        // The new Subcategory should belong to the 'null' Category
        $this->assertEquals(
            'New Subcategory',
            $category->subcategories->where('id', $subcategory->id)->first()->name
        );
        $this->assertEquals(null, $subcategory->category->name);
    }

    /** @test */
    public function ListElementManager_can_create_a_new_Task()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::where('task_list_id', $list->id)->where('name', null)->first();
        $subcategory = Subcategory::where('category_id', $category->id)->where('name', null)->first();

        $task = ListElementManager::newListElement('task', 'New Task', $list);

        // The new Task and its containing 'null' Category & Subcategory should be in the ListElements
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'task', 'name' => 'New Task']
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'subcategory', 'name' => null]
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'category', 'name' => null]
        );

        // The new Task and its containing 'null' Category & Subcategory should be in their respective db tables
        $this->assertDatabaseHas(
            'tasks',
            ['subcategory_id' => $subcategory->id, 'name' => 'New Task']
        );
        $this->assertDatabaseHas(
            'subcategories',
            ['category_id' => $category->id, 'name' => null]
        );
        $this->assertDatabaseHas(
            'categories',
            ['task_list_id' => $list->id, 'name' => null]
        );

        // The new Task should belong to the 'null' Category & Subcategory
        $this->assertEquals(
            'New Task',
            $subcategory->tasks->where('id', $task->id)->first()->name
        );
        $this->assertEquals(null, $task->subcategory->name);
        $this->assertEquals(null, $task->subcategory->category->name);
    }
}

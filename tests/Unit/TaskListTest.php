<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\Managers\ItemManager;

class TaskListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_TaskList_can_be_created()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        
        $this->assertDatabaseHas('task_lists', ['id' => $list->id, 'name' => 'New List']);
    }

    /** @test */
    public function a_new_TaskList_gets_true_saved_status()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        
        $this->assertEquals(true, $list->saved);
    }

    /** @test */
    public function a_new_TaskList_gets_true_autodelete_status_by_default()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        
        $this->assertEquals(true, $list->autodelete);
    }

    /** @test */
    public function a_TaskList_belongs_to_a_User()
    {
        $user = factory(User::class)->create(['name' => 'testuser']);
        $list = TaskList::newTaskList($user, 'New List');

        $this->assertEquals('testuser', $list->user->name);
    }

    /** @test */
    public function a_TaskList_has_a_name()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');

        $this->assertEquals('New List', $list->name);
    }

    /** @test */
    public function a_TaskList_can_add_Categories()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');

        $newCategory = Category::newCategory($list, 'New Category');

        $this->assertDatabaseHas('categories', ['task_list_id' => $list->id, 'name' => 'New Category']);
    }

    /** @test */
    public function a_TaskList_can_access_its_Categories()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');

        $newCategory = Category::newCategory($list, 'New Category');

        $this->assertEquals(
            $newCategory->id,
            $list->categories->where('name', 'New Category')->first()->id
        );
    }

    /** @test */
    public function a_TaskList_has_an_array_of_its_priority_status_Tasks()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $task1 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id
        ]);

        $task2 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id,
            'status' => 'priority'
        ]);
        $task2->wasRecentlyCreated = false;

        $this->assertEquals([$task2], $list->priorities());
    }

    /** @test */
    public function a_TaskList_can_be_updated()
    {
        $user = factory(User::class)->create();
        $defaultList = TaskList::newDefaultTaskList($user);
        $list = TaskList::newTaskList($user, 'Original Name');
        
        $list->updateTaskList('New Name');
        
        $this->assertEquals('New Name', $list->name);
    }

    /** @test */
    public function a_TaskList_can_be_deleted()
    {
        $user = factory(User::class)->create();
        $defaultList = TaskList::newDefaultTaskList($user);
        $list = TaskList::newTaskList($user, 'List Name');
        
        $list->deleteTaskList();
        
        $this->assertDatabaseMissing('task_lists', ['id' => $list->id, 'name' => 'List Name']);
    }

    /** @test */
    public function a_default_TaskList_can_be_created()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newDefaultTaskList($user);
        
        $this->assertDatabaseHas('task_lists', ['id' => $list->id]);
    }

    /** @test */
    public function a_new_default_TaskList_is_named_after_the_current_date()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newDefaultTaskList($user);
        
        $today = (\Carbon\Carbon::today())->timezone($user->timezone);
        $todayFormatted = $today->format('l\, F jS'); // format like Friday, October 20th

        $this->assertEquals($todayFormatted, $list->name);
    }

    /** @test */
    public function a_new_default_TaskList_gets_false_saved_status()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newDefaultTaskList($user);
        
        $this->assertFalse($list->saved);
    }

    /** @test */
    public function a_new_default_TaskList_has_a_Category_and_Subcategory_with_null_name()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newDefaultTaskList($user);
        
        $this->assertDatabaseHas('categories', [
            'task_list_id' => $list->id,
            'name' => null
        ]);
        
        $this->assertDatabaseHas('subcategories', [
            'category_id' => $list->categories()->first()->id,
            'name' => null
        ]);
    }

    /** @test */
    public function an_unsaved_TaskList_can_get_an_updated_default_name_derived_from_the_current_date()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'Unsaved');
        
        $list->saved = false;
        $list->save();

        $list->resetNameByDate();

        $today = (\Carbon\Carbon::today())->timezone($user->timezone);
        $newDefaultName = $today->format('l\, F jS'); // format like Friday, October 20th
        
        $this->assertEquals($newDefaultName, $list->name);
    }

    /** @test */
    public function a_saved_TaskList_cannot_get_an_updated_default_name_derived_from_the_current_date()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'Saved');

        $list->resetNameByDate();

        $today = (\Carbon\Carbon::today())->timezone($user->timezone);
        $newDefaultName = $today->format('l\, F jS'); // format like Friday, October 20th
        
        $this->assertTrue($list->saved);
        $this->assertFalse($newDefaultName == $list->name);
        $this->assertEquals('Saved', $list->name);
    }

    /** @test */
    public function a_new_default_TaskList_is_created_if_the_old_one_is_renamed_and_saved()
    {
        $user = factory(User::class)->create();
        $originalDefaultList = TaskList::newDefaultTaskList($user);

        $originalDefaultList->updateTaskList('New Name');
        
        $this->assertDatabaseHas(
            'task_lists',
            [
                'id' => $originalDefaultList->id,
                'name' => 'New Name',
                'saved' => true
            ]
        );

        $this->assertDatabaseHas(
            'task_lists',
            [
                'id' => User::find($user->id)->getDefaultList()->id,
                'name' => (\Carbon\Carbon::today())->timezone($user->timezone)
                    ->format('l\, F jS'),
                'saved' => false
            ]
        );
        
        $this->assertFalse(
            $originalDefaultList->id == User::find($user->id)->getDefaultList()->id
        );
    }

    /** @test */
    public function a_new_default_TaskList_is_created_if_the_old_one_is_deleted()
    {
        $user = factory(User::class)->create();
        $originalDefaultList = TaskList::newDefaultTaskList($user);

        $originalDefaultList->deleteTaskList();
        
        $this->assertDatabaseMissing(
            'task_lists', ['id' => $originalDefaultList->id]
        );

        $this->assertDatabaseHas(
            'task_lists',
            [
                'id' => User::find($user->id)->getDefaultList()->id,
                'name' => (\Carbon\Carbon::today())->timezone($user->timezone)
                    ->format('l\, F jS'),
                'saved' => false
            ]
        );
        
        $this->assertFalse(
            $originalDefaultList->id == User::find($user->id)->getDefaultList()->id
        );
    }

    /** @test */
    public function a_TaskList_gets_a_new_list_element_when_a_new_category_is_added()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'category', 'name' => 'New Category']
        );

        $this->assertEquals(
            'New Category',
            $list->listElements->where('unique_id', $category->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_TaskList_gets_a_new_list_element_when_a_new_subcategory_is_added()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'subcategory', 'name' => 'New Subcategory']
        );

        $this->assertEquals(
            'New Subcategory',
            $list->listElements->where('unique_id', $subcategory->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_TaskList_gets_a_new_list_element_when_a_new_task_is_added()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        $task = Task::newTask($subcategory, 'New Task');
        
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'task', 'name' => 'New Task']
        );

        $this->assertEquals(
            'New Task',
            $list->listElements->where('unique_id', $task->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_TaskList_gets_a_new_list_element_when_a_new_task_item_is_added()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        $task = Task::newTask($subcategory, 'New Task');
        $item = ItemManager::newItem('detail', 'new detail', $task);
        
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'detail', 'name' => 'new detail']
        );

        $this->assertEquals(
            'new detail',
            $list->listElements->where('unique_id', $item->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_new_TaskList_has_a_last_time_loaded_prop_equal_to_its_creation_datetime()
    {
        $user = factory(User::class)->create();

        $testTime = (\Carbon\Carbon::now())->toDateTimeString();
        $list = TaskList::newTaskList($user, 'New List');
        
        $this->assertDatabaseHas(
            'task_lists',
            ['name' => 'New List', 'last_time_loaded' => $testTime]
        );

        $this->assertEquals($testTime, $list->last_time_loaded);
    }

    /** @test */
    public function a_TaskLists_last_time_loaded_prop_can_be_updated_to_match_current_DateTime()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        
        $list->updateLastTimeLoaded();
        
        $now = \Carbon\Carbon::now();
        $nowDateTime = $now->toDateTimeString();

        $this->assertEquals($nowDateTime, $list->last_time_loaded);
    }
}

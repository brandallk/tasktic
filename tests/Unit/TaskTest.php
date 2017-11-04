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
use App\Models\Managers\ItemManager;
use App\Models\Interfaces\iItem;
use App\Models\DeadlineItem;
use App\Models\Item;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private function makeNewSubcategory()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        
        return $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
    }

    /** @test */
    public function a_Task_can_be_created()
    {
        $subcategory = $this->makeNewSubcategory();
        $task = Task::newTask($subcategory, 'New Task');
        
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'New Task']);
        $this->assertInstanceOf(Task::class, $task);
    }

    /** @test */
    public function a_Task_has_a_name_and_list_element_id()
    {
        $subcategory = $this->makeNewSubcategory();
        $task = Task::newTask($subcategory, 'New Task');
        
        $this->assertEquals('New Task', $task->name);
        $this->assertFalse($task->list_element_id == null);
        $this->assertInternalType('string', $task->list_element_id);
    }

    /** @test */
    public function a_new_Task_has_status_equal_to_incomplete_by_default()
    {
        $subcategory = $this->makeNewSubcategory();
        $task = Task::newTask($subcategory, 'New Task');
        
        $this->assertEquals('incomplete', $task->status);
    }

    /** @test */
    public function a_new_Task_has_deadline_equal_to_null_by_default()
    {
        $subcategory = $this->makeNewSubcategory();
        $task = Task::newTask($subcategory, 'New Task');
        
        $this->assertEquals(null, $task->deadline);
    }

    /** @test */
    public function a_new_Task_can_be_assigned_a_deadline()
    {
        $subcategory = $this->makeNewSubcategory();
        $task = Task::newTask($subcategory, 'New Task', 'Monday, Oct. 24');
        
        $this->assertDatabaseHas('deadline_items', ['task_id' => $task->id, 'deadline' => 'Monday, Oct. 24']);
        $this->assertEquals('Monday, Oct. 24', $task->deadline);
        $this->assertEquals('Monday, Oct. 24', $task->deadlineItem->deadline);
    }

    /** @test */
    public function a_new_Task_that_is_assigned_a_deadline_gets_a_corresponding_task_item_automatically()
    {
        $subcategory = $this->makeNewSubcategory();
        $task = Task::newTask($subcategory, 'New Task', 'Monday, Oct. 24');
        
        $this->assertDatabaseHas('task_items', ['task_id' => $task->id, 'type' => 'deadline']);
        $this->assertEquals(
            'deadline',
            $task->taskItems->where('unique_id', $task->deadlineItem->list_element_id)->first()->type
        );
    }

    /** @test */
    public function a_Task_belongs_to_a_Subcategory()
    {
        $subcategory = $this->makeNewSubcategory();
        $task = Task::newTask($subcategory, 'Task Name');
        
        $this->assertEquals('Subcategory Name', $task->subcategory->name);
    }

    /** @test */
    public function a_new_Task_gets_added_to_its_parent_TaskList_listElements_automatically()
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
    public function a_Task_can_add_a_DetailItem()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'New Task');

        $detailItem = ItemManager::newItem('detail', 'new task detail', $task);

        $this->assertDatabaseHas('detail_items', ['task_id' => $task->id, 'detail' => 'new task detail']);
        $this->assertEquals(
            'new task detail',
            $task->detailItems->where('list_element_id', $detailItem->list_element_id)->first()->detail
        );
    }

    /** @test */
    public function a_Task_that_adds_a_DetailItem_gets_a_corresponding_TaskItem_automatically()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'New Task');

        $detailItem = ItemManager::newItem('detail', 'new task detail', $task);

        $this->assertDatabaseHas('task_items', ['task_id' => $task->id, 'type' => 'detail']);
        $this->assertEquals(
            'detail',
            $task->taskItems->where('unique_id', $detailItem->list_element_id)->first()->type
        );
    }

    /** @test */
    public function a_Task_can_add_a_LinkItem()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'New Task');

        $linkItem = ItemManager::newItem('link', 'http://www.example.com', $task);

        $this->assertDatabaseHas('link_items', ['task_id' => $task->id, 'link' => 'http://www.example.com']);
        $this->assertEquals(
            'http://www.example.com',
            $task->linkItems->where('list_element_id', $linkItem->list_element_id)->first()->link
        );
    }

    /** @test */
    public function a_Task_that_adds_a_LinkItem_gets_a_corresponding_TaskItem_automatically()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'New Task');

        $linkItem = ItemManager::newItem('link', 'http://www.example.com', $task);

        $this->assertDatabaseHas('task_items', ['task_id' => $task->id, 'type' => 'link']);
        $this->assertEquals(
            'link',
            $task->taskItems->where('unique_id', $linkItem->list_element_id)->first()->type
        );
    }

    /** @test */
    public function a_Task_name_can_be_updated()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'Task Name');
        
        $task->updateDetails('New Name');
        
        // The Task itself should be updated
        $this->assertEquals('New Name', $task->name);

        // The parent TaskList's corresponding ListElement should be updated
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'task', 'name' => 'Task Name']
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'task', 'name' => 'New Name']
        );
    }

    /** @test */
    public function a_Task_deadline_can_be_updated()
    {
        $subcategory = $this->makeNewSubcategory();
        $task1 = Task::newTask($subcategory, 'Task Name', 'Monday, Oct. 24');
        
        $task1->updateDetails(null, 'Friday, Oct. 27');
        
        $this->assertEquals('Friday, Oct. 27', $task1->deadlineItem->deadline);
        $this->assertEquals('Friday, Oct. 27', $task1->deadline);

        // Make sure Task::updateDetails method works even if no deadline exists
        $task2 = Task::newTask($subcategory, 'Another Task Name');
        
        $task2->updateDetails(null, 'Saturday, Oct. 28');
        $task2_deadlineItem = DeadlineItem::where('task_id', $task2->id)->first();
        
        $this->assertEquals('Saturday, Oct. 28', $task2_deadlineItem->deadline);
        $this->assertDatabaseHas('deadline_items', ['task_id' => $task2->id, 'deadline' => 'Saturday, Oct. 28']);
        $this->assertEquals('Saturday, Oct. 28', $task2->deadline);
    }

    /** @test */
    public function a_Task_name_and_deadline_can_be_updated_simultaneously()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'Task Name', 'Monday, Oct. 24');
        
        $task->updateDetails('New Name', 'Friday, Oct. 27');
        
        $this->assertEquals('New Name', $task->name);
        $this->assertEquals('Friday, Oct. 27', $task->deadlineItem->deadline);
        $this->assertEquals('Friday, Oct. 27', $task->deadline);
    }

    /** @test */
    public function Task_status_can_be_updated()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'Task Name');

        // default status
        $this->assertEquals('incomplete', $task->status);
        
        // change to 'complete' status
        $task->updateStatus('complete');
        
        $this->assertEquals('complete', $task->status);

        // change to 'priority' status
        $task->updateStatus('priority');
        
        $this->assertEquals('priority', $task->status);

        // change back to 'incomplete' status
        $task->updateStatus('incomplete');
        
        $this->assertEquals('incomplete', $task->status);
    }

    /** @test */
    public function a_Task_can_be_deleted()
    {
        $subcategory = $this->makeNewSubcategory();        
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');
        
        $task->deleteTask();
        
        $this->assertDatabaseMissing(
            'tasks',
            ['id' => $task->id, 'name' => 'Task Name']
        );

        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'task', 'name' => 'Task Name']
        );
    }
}

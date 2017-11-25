<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;

class TaskRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function registerNewUser()
    {
        $userDetails = [
            'name' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        
        $response = $this->post('/register', $userDetails);

        return User::where('name', 'newuser')->first();
    }

    /**
     * @test
     *
     * @dataProvider provideForStoreMethodRequestValidation
     */
    public function TaskController_store_method_returns_redirect_if_request_validation_fails(
        $name, $deadline, $subcategoryID)
    {
        $user = $this->registerNewUser();

        $requestData = [
            'name' => $name,
            'deadline' => $deadline,
            'subcategoryID' => $subcategoryID
        ];

        $response = $this->actingAs($user)
                         ->post("/tasks", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 5 data sets that should each fail the TaskController::store validation
    public function provideForStoreMethodRequestValidation()
    {
        return [
            [123, null, 1], // invalid name
            [null, null, 1], // missing name
            ['Subcategory Name', 123, 1], // invalid deadline
            ['Subcategory Name', null, 'notAnInteger'], // invalid subcategoryID
            ['Subcategory Name', null, null], // missing subcategoryID
        ];
    }

    /** @test */
    public function TaskController_store_method_creates_a_new_Task()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $requestData = [
            'name' => 'Task Name',
            'deadline' => 'Next Monday',
            'subcategoryID' => $subcategory->id
        ];

        $response = $this->actingAs($user)
                         ->post("/tasks", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'Task Name']);
    }

    /** @test */
    public function TaskController_store_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $requestData = [
            'name' => 'Task Name',
            'deadline' => 'Next Monday',
            'subcategoryID' => $subcategory->id
        ];

        $response = $this->actingAs($user)
                         ->post("/tasks", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /**
     * @test
     *
     * @dataProvider provideForUpdateDetailsMethodRequestValidationFailure
     */
    public function TaskController_updateDetails_method_returns_redirect_if_request_validation_fails(
        $name, $deadline)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'name' => $name,
            'deadline' => $deadline
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/details", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 2 data sets that should each fail the TaskController::updateDetails validation
    public function provideForUpdateDetailsMethodRequestValidationFailure()
    {
        return [
            [123, 'May 1'], // invalid name
            ['New Name', 123], // invalid deadline
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideForUpdateDetailsMethodRequestValidation
     */
    public function TaskController_updateDetails_method_will_accept_null_name_or_deadline(
        $name, $deadline)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'name' => $name,
            'deadline' => $deadline
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/details", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    // 3 data sets that should each fail the TaskController::updateDetails validation
    public function provideForUpdateDetailsMethodRequestValidation()
    {
        return [
            [null, 'May 1'], // missing name
            ['New Name', null], // missing deadline
            [null, null], // missing name and deadline
        ];
    }

    /** @test */
    public function TaskController_updateDetails_method_updates_a_Task_name_or_deadline()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'name' => 'New Name',
            'deadline' => 'New deadline'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/details", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'New Name', 'deadline' => 'New deadline']);
    }

    /** @test */
    public function TaskController_updateDetails_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'name' => 'New Name',
            'deadline' => 'New deadline'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/details", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /**
     * @test
     *
     * @dataProvider provideForUpdateStatusMethodRequestValidationFailure
     */
    public function TaskController_updateStatus_method_returns_redirect_if_request_validation_fails($status)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'status' => $status
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/status", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 2 data sets that should each fail the TaskController::updateStatus validation
    public function provideForUpdateStatusMethodRequestValidationFailure()
    {
        return [
            ['priority'], // invalid status
            [null], // missing status
        ];
    }

    /** @test */
    public function TaskController_updateStatus_method_toggles_a_Task_status()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $this->assertDatabaseHas('tasks', ['name' => 'Task Name', 'status' => 'incomplete']);

        $requestData = [
            'status' => 'complete'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/status", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'Task Name', 'status' => 'complete']);

        $requestData = [
            'status' => 'incomplete'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/status", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'Task Name', 'status' => 'incomplete']);
    }

    /** @test */
    public function TaskController_updateStatus_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'status' => 'complete'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/status", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /**
     * @test
     *
     * @dataProvider provideForUpdatePriorityMethodRequestValidationFailure
     */
    public function TaskController_updatePriority_method_returns_redirect_if_request_validation_fails($status)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'status' => $status
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/priority", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 2 data sets that should each fail the TaskController::updatePriority validation
    public function provideForUpdatePriorityMethodRequestValidationFailure()
    {
        return [
            ['complete'], // invalid status
            [null], // missing status
        ];
    }

    /** @test */
    public function TaskController_updatePriority_method_toggles_a_Task_status()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $this->assertDatabaseHas('tasks', ['name' => 'Task Name', 'status' => 'incomplete']);

        $requestData = [
            'status' => 'priority'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/priority", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'Task Name', 'status' => 'priority']);

        $requestData = [
            'status' => 'incomplete'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/priority", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'Task Name', 'status' => 'incomplete']);
    }

    /** @test */
    public function TaskController_updatePriority_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $requestData = [
            'status' => 'priority'
        ];

        $response = $this->actingAs($user)
                         ->patch("tasks/{$task->id}/priority", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /** @test */
    public function TaskController_reposition_method_reorders_Task_display_positions_when_a_Task_is_dragged_above_another()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $task1 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'task1',
            'display_position' => 1
        ]);

        $task2 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'task2',
            'display_position' => 2
        ]);

        $task3 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'task3',
            'display_position' => 3
        ]);

        $requestData = [
            'draggedTaskID' => $task3->id,
            'insertAbove' => 'true'
        ];

        // Drag $task3 over $task1
        $response = $this->actingAs($user)
                         ->patch("tasks/{$task1->id}/reposition", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'task3', 'display_position' => 1]);
        $this->assertDatabaseHas('tasks', ['name' => 'task1', 'display_position' => 2]);
        $this->assertDatabaseHas('tasks', ['name' => 'task2', 'display_position' => 3]);
    }

    /** @test */
    public function TaskController_reposition_method_reorders_Task_display_positions_when_a_Task_is_dragged_to_bottom_of_Subcat()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $task1 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'task1',
            'display_position' => 1
        ]);

        $task2 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'task2',
            'display_position' => 2
        ]);

        $task3 = factory(Task::class)->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'task3',
            'display_position' => 3
        ]);

        $requestData = [
            'draggedTaskID' => $task1->id,
            'insertBelow' => 'true'
        ];

        // Drag $task1 below $task3
        $response = $this->actingAs($user)
                         ->patch("tasks/{$task3->id}/reposition", $requestData);

        $this->assertDatabaseHas('tasks', ['name' => 'task2', 'display_position' => 2]);
        $this->assertDatabaseHas('tasks', ['name' => 'task3', 'display_position' => 3]);
        $this->assertDatabaseHas('tasks', ['name' => 'task1', 'display_position' => 4]);
    }

    /** @test */
    public function TaskController_destroy_method_deletes_a_Task()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $response = $this->actingAs($user)
                         ->delete("/tasks/{$task->id}");

        $this->assertDatabaseMissing('tasks', ['name' => 'Task Name']);
    }

    /** @test */
    public function TaskController_destroy_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');

        $response = $this->actingAs($user)
                         ->delete("/tasks/{$task->id}");

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }
}

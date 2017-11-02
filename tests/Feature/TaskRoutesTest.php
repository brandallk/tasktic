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
    public function TaskController_store_method_returns_the_list_show_view()
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
            ->assertSuccessful()
            ->assertViewIs('list.show')
            ->assertSee('Task Name');
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

        $response->assertSuccessful();
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
    public function TaskController_updateDetails_method_returns_the_list_show_view()
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
            ->assertSuccessful()
            ->assertViewIs('list.show')
            ->assertSee('New Name')
            ->assertSee('New deadline');
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
    public function TaskController_updateStatus_method_returns_the_list_show_view()
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
            ->assertSuccessful()
            ->assertViewIs('list.show');
    }
}

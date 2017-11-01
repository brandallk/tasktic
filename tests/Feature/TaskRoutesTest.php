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

    // 4 data sets that should each fail the TaskController::store validation
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
}

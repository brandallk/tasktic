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
use App\Models\Managers\ItemManager;

class ItemRoutesTest extends TestCase
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
    public function ItemController_store_method_returns_redirect_if_request_validation_fails(
        $type, $content, $taskID)
    {
        $user = $this->registerNewUser();

        $requestData = [
            'type' => $type,
            'content' => $content,
            'taskID' => $taskID
        ];

        $response = $this->actingAs($user)
                         ->post("/items", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 6 data sets that should each fail the ItemController::store validation
    public function provideForStoreMethodRequestValidation()
    {
        return [
            ['causeValidationFailure', 'some content text', 1], // invalid type
            [null, 'some content text', 1], // missing type
            ['detail', 123, 1], // invalid content
            ['detail', null, 1], // missing content
            ['detail', 'some content text', 'notATaskID'], // invalid taskID
            ['detail', 'some content text', null], // missing taskID
        ];
    }

    /** @test */
    public function ItemController_store_method_creates_a_new_iItem()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name');

        $requestData = [
            'type' => 'detail',
            'content' => 'A new task detail.',
            'taskID' => $task->id
        ];

        $response = $this->actingAs($user)
                         ->post("/items", $requestData);

        $this->assertDatabaseHas('detail_items', ['detail' => 'A new task detail.']);

        $requestData = [
            'type' => 'link',
            'content' => 'http://somelinkurl.com',
            'taskID' => $task->id
        ];

        $response = $this->actingAs($user)
                         ->post("/items", $requestData);

        $this->assertDatabaseHas('link_items', ['link' => 'http://somelinkurl.com']);
    }

    /** @test */
    public function ItemController_store_method_returns_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name');

        $requestData = [
            'type' => 'detail',
            'content' => 'A new task detail.',
            'taskID' => $task->id
        ];

        $response = $this->actingAs($user)
                         ->post("/items", $requestData);

        $response
            ->assertSuccessful()
            ->assertViewIs('list.show')
            ->assertSee('A new task detail.');
    }

    /**
     * @test
     *
     * @dataProvider provideForUpdateDetailMethodRequestValidationFailure
     */
    public function ItemController_updateDetail_method_returns_redirect_if_request_validation_fails($content)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');
        $item = ItemManager::newItem('detail', 'A task detail.', $task);

        $requestData = [
            'content' => $content
        ];

        $response = $this->actingAs($user)
                         ->patch("/items/detail/{$item->id}", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 2 data sets that should each fail the ItemController::updateDetail validation
    public function provideForUpdateDetailMethodRequestValidationFailure()
    {
        return [
            [123], // invalid content
            [null], // missing content
        ];
    }

    /** @test */
    public function ItemController_updateDetail_method_updates_an_DetailItem_content()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');
        $item = ItemManager::newItem('detail', 'A task detail.', $task);

        $requestData = [
            'content' => 'Some other task detail.'
        ];

        $response = $this->actingAs($user)
                         ->patch("/items/detail/{$item->id}", $requestData);

        $this->assertDatabaseHas('detail_items', ['type' => 'detail', 'detail' => 'Some other task detail.']);
    }

    /** @test */
    public function ItemController_updateDetail_method_returns_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        $task = Task::newTask($subcategory, 'Task Name', 'July 4');
        $item = ItemManager::newItem('detail', 'A task detail.', $task);

        $requestData = [
            'content' => 'Some other task detail.'
        ];

        $response = $this->actingAs($user)
                         ->patch("/items/detail/{$item->id}", $requestData);

        $response
            ->assertSuccessful()
            ->assertViewIs('list.show')
            ->assertSee('Some other task detail.');
    }
}
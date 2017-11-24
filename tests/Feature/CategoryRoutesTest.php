<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;

class CategoryRoutesTest extends TestCase
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
    public function CategoryController_store_method_returns_redirect_if_request_validation_fails(
        $name, $currentListID)
    {
        $user = $this->registerNewUser();

        $requestData = [
            'name' => $name,
            'currentListID' => $currentListID
        ];

        $response = $this->actingAs($user)
                         ->post("/categories", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 4 data sets that should each fail the CategoryController::store validation
    public function provideForStoreMethodRequestValidation()
    {
        return [
            [123, 1], // invalid name
            [null, 1], // missing name
            ['Category Name', 'notAnInteger'], // invalid currentListID
            ['Category Name', null], // missing currentListID
        ];
    }

    /** @test */
    public function CategoryController_store_method_creates_a_new_Category()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        $requestData = [
            'name' => 'Category Name',
            'currentListID' => $list->id
        ];

        $response = $this->actingAs($user)
                         ->post("/categories", $requestData);

        $this->assertDatabaseHas('categories', ['name' => 'Category Name']);
    }

    /** @test */
    public function CategoryController_store_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        $requestData = [
            'name' => 'Category Name',
            'currentListID' => $list->id
        ];

        $response = $this->actingAs($user)
                         ->post("/categories", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /**
     * @test
     *
     * @dataProvider provideForUpdateMethodRequestValidation
     */
    public function CategoryController_update_method_returns_redirect_if_request_validation_fails($name)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');

        $requestData = [
            'name' => $name
        ];

        $response = $this->actingAs($user)
                         ->patch("/categories/{$category->id}", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 4 data sets that should each fail the CategoryController::update validation
    public function provideForUpdateMethodRequestValidation()
    {
        return [
            [123], // invalid name
            [null], // missing name
        ];
    }

    /** @test */
    public function CategoryController_update_method_updates_a_Category_name()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');

        $requestData = [
            'name' => 'New Name'
        ];

        $response = $this->actingAs($user)
                         ->patch("/categories/{$category->id}", $requestData);

        $this->assertDatabaseHas('categories', ['name' => 'New Name']);
    }

    /** @test */
    public function CategoryController_update_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');

        $requestData = [
            'name' => 'New Name'
        ];

        $response = $this->actingAs($user)
                         ->patch("/categories/{$category->id}", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /** @test */
    public function CategoryController_destroy_method_deletes_a_Category()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');

        $response = $this->actingAs($user)
                         ->delete("/categories/{$category->id}");

        $this->assertDatabaseMissing('categories', ['name' => 'Category Name']);
    }

    /** @test */
    public function CategoryController_destroy_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');

        $response = $this->actingAs($user)
                         ->delete("/categories/{$category->id}");

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }
}

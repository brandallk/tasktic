<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategoryRoutesTest extends TestCase
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
    public function SubcategoryController_store_method_returns_redirect_if_request_validation_fails(
        $name, $categoryID)
    {
        $user = $this->registerNewUser();

        $requestData = [
            'name' => $name,
            'categoryID' => $categoryID
        ];

        $response = $this->actingAs($user)
                         ->post("/subcategories", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 4 data sets that should each fail the SubcategoryController::store validation
    public function provideForStoreMethodRequestValidation()
    {
        return [
            [123, 1], // invalid name
            [null, 1], // missing name
            ['Category Name', 'notAnInteger'], // invalid categoryID
            ['Category Name', null], // missing categoryID
        ];
    }

    /** @test */
    public function SubcategoryController_store_method_creates_a_new_Subcategory()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');

        $requestData = [
            'name' => 'Subcategory Name',
            'categoryID' => $category->id
        ];

        $response = $this->actingAs($user)
                         ->post("/subcategories", $requestData);

        $this->assertDatabaseHas('subcategories', ['name' => 'Subcategory Name']);
    }

    /** @test */
    public function SubcategoryController_store_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');

        $requestData = [
            'name' => 'Subcategory Name',
            'categoryID' => $category->id
        ];

        $response = $this->actingAs($user)
                         ->post("/subcategories", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /**
     * @test
     *
     * @dataProvider provideForUpdateMethodRequestValidation
     */
    public function SubcategoryController_update_method_returns_redirect_if_request_validation_fails($name)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $requestData = [
            'name' => $name
        ];

        $response = $this->actingAs($user)
                         ->patch("/subcategories/{$subcategory->id}", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 4 data sets that should each fail the SubcategoryController::update validation
    public function provideForUpdateMethodRequestValidation()
    {
        return [
            [123], // invalid name
            [null], // missing name
        ];
    }

    /** @test */
    public function SubcategoryController_update_method_updates_a_Subcategory_name()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $requestData = [
            'name' => 'New Name'
        ];

        $response = $this->actingAs($user)
                         ->patch("/subcategories/{$subcategory->id}", $requestData);

        $this->assertDatabaseHas('subcategories', ['name' => 'New Name']);
    }

    /** @test */
    public function SubcategoryController_update_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $requestData = [
            'name' => 'New Name'
        ];

        $response = $this->actingAs($user)
                         ->patch("/subcategories/{$subcategory->id}", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /** @test */
    public function SubcategoryController_destroy_method_deletes_a_Subcategory()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $response = $this->actingAs($user)
                         ->delete("/subcategories/{$subcategory->id}");

        $this->assertDatabaseMissing('subcategories', ['name' => 'Subcategory Name']);
    }

    /** @test */
    public function SubcategoryController_destroy_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');

        $response = $this->actingAs($user)
                         ->delete("/subcategories/{$subcategory->id}");

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }
}

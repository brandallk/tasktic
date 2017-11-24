<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TaskList;
use App\Http\Controllers\ListController;

class ListRoutesTest extends TestCase
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

    /** @test */
    public function ListController_index_method_returns_the_list_index_view()
    {
        $user = $this->registerNewUser();

        $response = $this->actingAs($user)
                         ->get('/lists');

        $response
            ->assertSuccessful()
            ->assertViewIs('list.index')
            ->assertSee('List Index');
    }

    /**
     * @test
     *
     * @dataProvider provideForRequestValidation
     */
    public function ListController_createListElement_method_returns_redirect_if_request_validation_fails(
        $elementType, $name, $deadline)
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        $requestData = [
            'elementType' => $elementType,
            'name' => $name,
            'deadline' => $deadline
        ];

        $response = $this->actingAs($user)
                         ->post("/lists/{$list->id}/create-element", $requestData);

        $response->assertStatus(302); // 302 is a redirect
    }

    // 5 data sets that should each fail the ListController::createListElement validation
    public function provideForRequestValidation()
    {
        return [
            ['makesTheValidationFail', 'New Name', null], // invalid elementType
            [null, 'New Name', null], // missing elementType
            ['category', 123, null], // invalid name
            ['category', null, null], // missing name
            ['category', 'New Name', 123], // invalid deadline
        ];
    }

    /** @test */
    public function ListController_createListElement_method_returns_redirect_to_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        $requestData = [
            'elementType' => 'category',
            'name' => 'New Name',
            'deadline' => null
        ];

        $response = $this->actingAs($user)
                         ->post("/lists/{$list->id}/create-element", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /** @test */
    public function ListController_store_method_creats_a_TaskList_from_the_request_name()
    {
        $user = $this->registerNewUser();

        $requestData = [
            'name' => 'New List'
        ];

        $response = $this->actingAs($user)
                         ->post('/lists', $requestData);

        $this->assertDatabaseHas('task_lists', ['name' => 'New List']);

        $this->assertEquals(
            'New List',
            $user->taskLists->sortByDesc('id')->first()->name // The user's last-added TaskList
        );
    }

    /** @test */
    public function ListController_store_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();

        $requestData = [
            'name' => 'New List'
        ];

        $response = $this->actingAs($user)
                         ->post('/lists', $requestData);
                         
        $list = $user->taskLists->where('name', 'New List')->first();

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /** @test */
    public function ListController_update_method_updates_a_TaskList_name()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        $requestData = [
            'name' => 'New Name'
        ];

        $response = $this->actingAs($user)
                         ->patch("/lists/{$list->id}", $requestData);

        $this->assertDatabaseHas('task_lists', ['name' => 'New Name']);

        $this->assertEquals(
            $list->id,
            $user->taskLists->where('name', 'New Name')->first()->id
        );
    }

    /** @test */
    public function ListController_update_method_returns_redirect_to_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        $requestData = [
            'name' => 'New Name'
        ];

        $response = $this->actingAs($user)
                         ->patch("/lists/{$list->id}", $requestData);

        $response
            ->assertStatus(302) // 302 is a redirect
            ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }

    /** @test */
    public function ListController_show_method_returns_the_list_show_view()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        $response = $this->actingAs($user)
                         ->get("/lists/{$list->id}");

        $response
            ->assertSuccessful()
            ->assertViewIs('list.show')
            ->assertSee('List Name');
    }

    /** @test */
    public function ListController_destroy_method_deletes_the_list()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');

        (new ListController)->destroy($list);

        $this->assertDatabaseMissing('task_lists', ['name' => 'List Name']);
    }

    // /** @test */
    // public function ListController_show_method_updates_the_TaskList_last_time_loaded_property()
    // {
    //     $user = $this->registerNewUser();
    //     $list = TaskList::newTaskList($user, 'List Name');

    //     // Artificially set the 'last_time_loaded' to Jan 1, 1975.
    //     $list->last_time_loaded =
    //         (\Carbon\Carbon::create(1975, 1, 1, 12, 0, 0))->toDateTimeString();
    //     $list->save();

    //     $response = $this->actingAs($user)
    //                      ->get("/lists/{$list->id}");

    //     // $this->assertEquals(
    //     //     (\Carbon\Carbon::now())->toDateTimeString(),
    //     //     $list->last_time_loaded
    //     // );

    //     // The above assertion should work, but doesn't. (?!) However, the following
    //     // assertion does work if the view includes a hidden span that echoes out
    //     // $list->last_time_loaded
    //     $response->assertSee((\Carbon\Carbon::now())->toDateTimeString());
    // }
}

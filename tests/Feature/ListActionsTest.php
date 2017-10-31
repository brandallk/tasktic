<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TaskList;

class ListActionsTest extends TestCase
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
}

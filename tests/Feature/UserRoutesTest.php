<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use Illuminate\Support\Facades\Auth;

class UserRoutesTest extends TestCase
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
    public function a_User_can_register()
    {
        $user = $this->registerNewUser();

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);
        $this->assertEquals(Auth::user()->name, 'newuser');
    }

    /** @test */
    public function a_new_User_gets_visitor_role_by_default()
    {
        $user = $this->registerNewUser();

        $this->assertEquals('visitor', $user->role);
    }

    /** @test */
    public function a_new_User_gets_a_default_timezone()
    {
        $user = $this->registerNewUser();

        $this->assertEquals('UTC', $user->timezone);
    }

    /** @test */
    public function UserContoller_storeTimezone_method_changes_the_users_stored_timezone()
    {
        $user = $this->registerNewUser();
        $list = TaskList::newTaskList($user, 'List Name');
        $OffsetFromUTCinMinutesAtLosAngeles = -480;

        $requestData = [
            'tzOffsetMinutes' => $OffsetFromUTCinMinutesAtLosAngeles
        ];

        $response = $this->actingAs($user)
                         ->post("/{$user->id}/{$list->id}/timezone", $requestData);

        $this->assertDatabaseMissing('users', ['id' => $user->id, 'timezone' => 'UTC']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'timezone' => 'America/Los_Angeles']);
        $this->assertEquals('America/Los_Angeles', $user->where('id', $user->id)->first()->timezone);

        $response->assertStatus(302) // 302 is a redirect
                 ->assertHeader('Location', "http://tasktic.dev/lists/{$list->id}");
    }
}

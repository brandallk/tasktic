<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TaskList;

class UserController extends Controller
{

    /**
     * Convert a timezone offset from UTC (as reported in minutes by JavaScript running
     * in the user's browser) to a timezone name and save it to the User instance.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function storeTimezone(Request $request, User $user, TaskList $list)
    {
        $storeTimezone = function($args) {
            extract($args);

            $user->setTimezone($request->tzOffsetMinutes);
            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $storeTimezone,
            $args = ['user' => $user, 'request' => $request, 'list' => $list]
        );
    }
}

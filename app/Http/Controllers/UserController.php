<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TaskList;

class UserController extends Controller
{
    /**
     * Convert a timezone offset from UTC (as reported in minutes by JavaScript running
     * in the user's browser) to a timezone name and assign it to the User instance.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function storeTimezone(Request $request, User $user, TaskList $list)
    {
        try{
            $timezoneOffsetSeconds = ($request->timezoneOffsetMinutes)*60;

            $timezoneName = timezone_name_from_abbr("", $timezoneOffsetSeconds, false);

            $user->timezone = $timezoneName;
            $user->save();

            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }
}

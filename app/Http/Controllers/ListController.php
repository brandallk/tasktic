<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;
use App\Models\Managers\ListElementManager;

class ListController extends Controller
{
    /**
     * Show an index of the all the user's (default and) saved TaskLists.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index = function($args) {

            $data = ['lists' => Auth::user()->taskLists];

            return view('list.index', $data);
        };

        return $this->tryOrCatch(
            $index,
            $args = []
        );
    }

    /**
     * Create a new Category or Task instance belonging to the current TaskList
     *
     * @param Illuminate\Http\Request $request
     * @param TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    public function createListElement(Request $request, TaskList $list)
    {
        $request->validate([
            'name'        => 'required|string',
            'deadline'    => 'nullable|string',
            'elementType' => [
                'required',
                'regex:/^category|task$/i' // 'category' or 'task'
            ]
        ]);

        $createListElement = function($args) {
            extract($args);

            ListElementManager::newListElement(
                $request->elementType,
                $request->name,
                $list,
                $request->deadline
            );

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $createListElement,
            $args = ['request' => $request, 'list' => $list]
        );
    }

    /**
     * Create a new TaskList instance and show it.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $store = function($args) {
            extract($args);

            $user = Auth::user();
            $name = $request->name;

            $newList = TaskList::newTaskList($user, $name);

            return redirect()->route('lists.show', ['list' => $newList->id]);
        };

        return $this->tryOrCatch(
            $store,
            $args = ['request' => $request]
        );
    }

    /**
     * Update the given TaskList.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskList $list)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $update = function($args) {
            extract($args);

            $name = $request->name;

            $updatedList = $list->updateTaskList($name);

            return redirect()->route('lists.show', ['list' => $updatedList->id]);
        };

        return $this->tryOrCatch(
            $update,
            $args = ['request' => $request, 'list' => $list]
        );
    }

    /**
     * Show the given TaskList in the 'list.show' view.
     *
     * @param App\Models\TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    public function show(TaskList $list)
    {
        $show = function($args) {
            extract($args);

            // Update the TaskList's 'last_time_loaded' property
            $list->updateLastTimeLoaded();

            // Get the timezone offset from UTC, based on the User's stored timezone
            $dtz                  = new \DateTimeZone(Auth::user()->timezone);
            $secondsOffsetFromUTC = $dtz->getOffset(new \DateTime("now", $dtz));
            $offsetMinutes        = $secondsOffsetFromUTC/60;

            $data = [
                'user'          => Auth::user(),
                'list'          => $list,
                'offsetMinutes' => $offsetMinutes
            ];

            return view('list.show', $data);
        };

        return $this->tryOrCatch(
            $show,
            $args = ['list' => $list]
        );
    }

    /**
     * Show the given TaskList's 'priority' tasks.
     *
     * @param App\Models\TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    public function priorities(TaskList $list)
    {
        $priorities = function($args) {
            extract($args);

            $data = [
                'list'       => $list,
                'priorities' => $list->priorities()
            ];

            return view('list.priorities.show', $data);
        };

        return $this->tryOrCatch(
            $priorities,
            $args = ['list' => $list]
        );
    }

    /**
     * Delete the given TaskList. (Reserved for possible future use: No route currently
     * calls this method. Users cannot currently delete their lists. Instead, inactive
     * lists are automatically deleted after 120 days.)
     *
     * @param App\Models\TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskList $list)
    {
        $destroy = function($args) {
            extract($args);

            $list->deleteTaskList();

            return redirect()->route('lists.index');
        };

        return $this->tryOrCatch(
            $destroy,
            $args = ['list' => $list]
        );
    }
}

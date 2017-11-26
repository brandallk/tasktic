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
        try {
            $data = [
                'lists' => Auth::user()->taskLists
            ];

            return view('list.index', $data);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    /**
     * Create a new Category, Subcategory, or Task instance belonging to the
     * current TaskList
     *
     * @param Illuminate\Http\Request $request
     * @param TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    public function createListElement(Request $request, TaskList $list)
    {
        $request->validate([
            'elementType' => [
                'required',                
                // elementType must be 'category', 'subcategory', or 'task'
                'regex:/^(sub)?category|task$/i'
            ],
            'name' => 'required|string',
            'deadline' => 'nullable|string'
        ]);

        try {
            ListElementManager::newListElement(
                $request->elementType,
                $request->name,
                $list,
                $request->deadline
            );

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
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

        try {
            $user = Auth::user();
            $name = $request->name;

            $newList = TaskList::newTaskList($user, $name);

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $newList->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
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

        try {
            $name = $request->name;

            $updatedList = $list->updateTaskList($name);

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $updatedList->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
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
        try {
            // Update the TaskList's 'last_time_loaded' property
            $list->updateLastTimeLoaded();

            // Get the timezone offset from UTC, based on the User's stored timezone
            $dtz = new \DateTimeZone(Auth::user()->timezone);
            $secondsOffsetFromUTC = $dtz->getOffset(new \DateTime("now", $dtz));
            $offsetMinutes = $secondsOffsetFromUTC/60;

            $data = [
                'user' => Auth::user(),
                'list' => $list,
                'offsetMinutes' => $offsetMinutes
            ];

            return view('list.show', $data);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
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
       try {
            $data = [
                'list' => $list,
                'priorities' => $list->priorities()
            ];

            return view('list.priorities.show', $data);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
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
        try {

            $list->deleteTaskList();

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.index');

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }
}

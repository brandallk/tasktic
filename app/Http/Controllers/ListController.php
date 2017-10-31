<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;

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

            return $this->show($newList); // test that response is the list.show view (after show method is added)

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

            $updatedList = $list->updateTaskList($list, $name);

            return $this->show($updatedList); // test that response is the list.show view (after show method is added)

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
            $showingList = $list->updateLastTimeLoaded($list); // test that the method updates the property

            $data = [
                'list' => $showingList
            ];

            return view('list.show', $data); // test that the method returns the view

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Return the 'list.show' view.
     *
     * @param App\Models\TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    private function showListView(TaskList $list)
    {
        $data = [
                'user' => Auth::user(),
                'list' => $list
            ];

            return view('list.show', $data);
    }

    /**
     * Create a new Task instance and show it.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'deadline' => 'nullable|string',
            'subcategoryID' => 'required|integer'
        ]);

        try {
            $subcategory = Subcategory::find($request->subcategoryID);
            $list = $subcategory->category->taskList;
            $name = $request->name;
            $deadline = $request->deadline;

            Task::newTask($subcategory, $name, $deadline);

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    /**
     * Update the given Task's name or deadline.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDetails(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'nullable|string',
            'deadline' => 'nullable|string'
        ]);

        try {
            $list = $task->subcategory->category->taskList;
            $name = $request->name;
            $deadline = $request->deadline;

            $task->updateDetails($name, $deadline);

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    /**
     * Update the given Task's status: Toggle between 'incomplete' and 'complete'.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => [
                'required',                
                // status must be 'complete' or 'incomplete'
                'regex:/^(in)?complete$/i'
            ],
        ]);

        try {
            $list = $task->subcategory->category->taskList;
            $status = $request->status;

            $task->updateStatus($status);

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    /**
     * Update the given Task's priority status: Toggle between 'incomplete' and 'priority'.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePriority(Request $request, Task $task)
    {
        $request->validate([
            'status' => [
                'required',                
                // status must be 'incomplete' or 'priority'
                'regex:/^incomplete|priority$/i'
            ],
        ]);

        try {
            $list = $task->subcategory->category->taskList;
            $status = $request->status;

            $task->updateStatus($status);

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    /**
     * Delete the given Task.
     *
     * @param App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            $list = $task->subcategory->category->taskList;
            $task->deleteTask();

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}

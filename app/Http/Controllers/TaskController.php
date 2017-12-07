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
     * Create a new Task instance and show it.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'deadline'      => 'nullable|string',
            'subcategoryID' => 'required|integer'
        ]);

        $store = function($args) {
            extract($args);

            $subcategory = Subcategory::find($request->subcategoryID);
            $list        = $subcategory->category->taskList;
            $name        = $request->name;
            $deadline    = $request->deadline;

            Task::newTask($subcategory, $name, $deadline);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $store,
            $args = ['request' => $request]
        );
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
            'name'     => 'nullable|string',
            'deadline' => 'nullable|string'
        ]);

        $updateDetails = function($args) {
            extract($args);

            $list     = $task->subcategory->category->taskList;
            $name     = $request->name;
            $deadline = $request->deadline;

            $task->updateDetails($name, $deadline);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $updateDetails,
            $args = ['request' => $request, 'task' => $task]
        );
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
                'regex:/^(in)?complete$/i' // 'complete' or 'incomplete'
            ],
        ]);

        $updateStatus = function($args) {
            extract($args);

            $list   = $task->subcategory->category->taskList;
            $status = $request->status;

            $task->updateStatus($status);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $updateStatus,
            $args = ['request' => $request, 'task' => $task]
        );
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
                'regex:/^incomplete|priority$/i' // 'incomplete' or 'priority'
            ],
        ]);

        $updatePriority = function($args) {
            extract($args);

            $list   = $task->subcategory->category->taskList;
            $status = $request->status;

            $task->updateStatus($status);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $updatePriority,
            $args = ['request' => $request, 'task' => $task]
        );
    }

    /**
     * Handle drag-and-drop re-ordering of Task display position within a
     * Subcategory <div>
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function reposition(Request $request, Task $task)
    {
        $reposition = function($args) {
            extract($args);

            $movedTask   = Task::find($request->draggedTaskID);
            $insertSite  = $task;
            $insertAbove = $request->insertAbove ? true : false;
            $insertBelow = $request->insertBelow ? true : false;

            $movedTask->changeDisplayPosition($insertSite, $insertAbove, $insertBelow);
            $list = $task->subcategory->category->taskList;

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $reposition,
            $args = ['request' => $request, 'task' => $task]
        );
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
        $destroy = function($args) {
            extract($args);

            $list = $task->subcategory->category->taskList;

            $task->deleteTask();

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $destroy,
            $args = ['task' => $task]
        );
    }
}

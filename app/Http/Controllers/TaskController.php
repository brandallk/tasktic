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

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    public function reorderDisplayPosition(Request $request, Task $task)
    {
        try {
            // $task::reorderDisplayPosition($droppedTask, $draggedTask, $insertAbove, $insertBelow);

            $draggedTask = Task::find($request->draggedTaskID);
            $droppedTask = $task;

            if ($droppedTask->subcategory->id == $draggedTask->subcategory->id) {

                $droppedTaskDispOrd = $droppedTask->display_position;

                $subcatTasks = [];
                foreach ($task->subcategory->tasks->sortBy('display_position') as $task) {
                    $subcatTasks[] = $task;
                }

                if ($request->insertAbove) {

                    for ($i=0; $i < count($subcatTasks); $i++) {
                        if ($subcatTasks[$i]->id != $draggedTask->id) {
                            if ($subcatTasks[$i]->display_position < $droppedTaskDispOrd) {
                                $subcatTasks[$i]->display_position = $i + 1;
                                $subcatTasks[$i]->save();
                            } elseif ($subcatTasks[$i]->display_position >= $droppedTaskDispOrd) {
                                $subcatTasks[$i]->display_position += 1;
                                $subcatTasks[$i]->save();
                            }
                        }                
                    }

                    $draggedTask->display_position = $droppedTaskDispOrd;
                    $draggedTask->save();

                } elseif ($request->insertBelow) {
                    
                    for ($i=0; $i < count($subcatTasks); $i++) {
                        if ($subcatTasks[$i]->id != $draggedTask->id) {
                            $subcatTasks[$i]->display_position = $i + 1;
                            $subcatTasks[$i]->save();
                        }                
                    }

                    $lastDisplayedSubcatTask = $subcatTasks[count($subcatTasks) - 1];

                    $draggedTask->display_position = $lastDisplayedSubcatTask->display_position + 1;
                    $draggedTask->save();

                }

            }

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            $list = $task->subcategory->category->taskList;
            return redirect()->route('lists.show', ['list' => $list->id]);

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

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}

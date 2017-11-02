<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\Managers\ItemManager;
use App\Models\Interfaces\iItem;

class ItemController extends Controller
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
     * Create a new DetailItem or LinkItem instance and show it.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => [
                'required',                
                // type must be 'detail' or 'link'
                'regex:/^detail|link$/i'
            ],
            'content' => 'required|string',
            'taskID' => 'required|integer',
        ]);

        try {
            $task = Task::find($request->taskID);
            $list = $task->subcategory->category->taskList;
            $type = $request->type;
            $content = $request->content;

            ItemManager::newItem($type, $content, $task);

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    /**
     * Update the given iItem.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Interfaces\iItem $item
     *
     * @return \Illuminate\Http\Response
     */
    // public function updateDetails(Request $request, iItem $item)
    // {
    //     $request->validate([
            
    //     ]);

    //     try {
            

    //         return $this->showListView($list);

    //     } catch (\Throwable $e) {
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         return redirect()->back();            
    //     }
    // }

    /**
     * Delete the given iItem.
     *
     * @param App\Models\Interfaces\iItem $item
     *
     * @return \Illuminate\Http\Response
     */
    // public function destroy(iItem $item)
    // {
    //     try {
            

    //         return $this->showListView($list);

    //     } catch (\Throwable $e) {
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         return redirect()->back();
    //     }
    // }
}

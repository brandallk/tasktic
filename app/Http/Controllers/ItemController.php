<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\Managers\ItemManager;
use App\Models\Interfaces\iItem;
use App\Models\DeadlineItem;
use App\Models\DetailItem;
use App\Models\LinkItem;

class ItemController extends Controller
{
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
            'content' => 'required|string',
            'taskID'  => 'required|integer',
            'type'    => [
                'required',
                'regex:/^detail|link$/i' // 'detail' or 'link'
            ]
        ]);

        try {

            $task    = Task::find($request->taskID);
            $list    = $task->subcategory->category->taskList;
            $type    = $request->type;
            $content = $request->content;

            ItemManager::newItem($type, $content, $task);

            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
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
    private function update(Request $request, iItem $item)
    {
        $task    = $item->task;
        $list    = $task->subcategory->category->taskList;
        $content = $request->content;

        $item->updateItem($task, $content);

        return redirect()->route('lists.show', ['list' => $list->id]);
    }

    /**
     * Update the given DetailItem.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\DetailItem $item
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDetail(Request $request, DetailItem $item)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {

            return $this->update($request, $item);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
    }

    /**
     * Update the given LinkItem.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\LinkItem $item
     *
     * @return \Illuminate\Http\Response
     */
    public function updateLink(Request $request, LinkItem $item)
    {
        $request->validate([
            'content' => 'required|url'
        ]);

        try {

            return $this->update($request, $item);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
    }

    /**
     * Delete the given iItem.
     *
     * @param App\Models\Interfaces\iItem $item
     *
     * @return \Illuminate\Http\Response
     */
    private function destroy(iItem $item)
    {
        $type     = $item->type;
        $uniqueID = $item->list_element_id;
        $task     = $item->task;
        $list     = $task->subcategory->category->taskList;

        ItemManager::deleteItem($type, $uniqueID, $task);

        return redirect()->route('lists.show', ['list' => $list->id]);
    }

    /**
     * Delete the given DeadlineItem.
     *
     * @param App\Models\DeadlineItem $item
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyDeadline(DeadlineItem $item)
    {
        try {

            return $this->destroy($item);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
    }

    /**
     * Delete the given DetailItem.
     *
     * @param App\Models\DetailItem $item
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyDetail(DetailItem $item)
    {
        try {

            return $this->destroy($item);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
    }

    /**
     * Delete the given LinkItem.
     *
     * @param App\Models\LinkItem $item
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyLink(LinkItem $item)
    {
        try {

            return $this->destroy($item);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
    }
}

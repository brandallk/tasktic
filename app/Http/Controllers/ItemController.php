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

        $store = function($args) {
            extract($args);

            $task    = Task::find($request->taskID);
            $list    = $task->subcategory->category->taskList;
            $type    = $request->type;
            $content = $request->content;

            ItemManager::newItem($type, $content, $task);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $store,
            $args = ['request' => $request]
        );
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

        $updateDetail = function($args) {
            extract($args);

            return $this->update($request, $item);
        };

        return $this->tryOrCatch(
            $updateDetail,
            $args = ['request' => $request, 'item' => $item]
        );
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

        $updateLink = function($args) {
            extract($args);

            return $this->update($request, $item);
        };

        return $this->tryOrCatch(
            $updateLink,
            $args = ['request' => $request, 'item' => $item]
        );
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
        $destroyDeadline = function($args) {
            extract($args);

            return $this->destroy($item);
        };

        return $this->tryOrCatch(
            $destroyDeadline,
            $args = ['item' => $item]
        );
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
        $destroyDetail = function($args) {
            extract($args);

            return $this->destroy($item);
        };

        return $this->tryOrCatch(
            $destroyDetail,
            $args = ['item' => $item]
        );
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
        $destroyLink = function($args) {
            extract($args);

            return $this->destroy($item);
        };

        return $this->tryOrCatch(
            $destroyLink,
            $args = ['item' => $item]
        );
    }
}

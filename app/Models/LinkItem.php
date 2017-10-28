<?php
/**
 * A URL string provided by the user, providing a way to create a clickable link relevant
 * to its parent Task.
 */

namespace App\Models;

use DB;
use App\Models\Item;
use App\Models\ListElement;
use App\Models\Interfaces\iItem;

class LinkItem extends Item
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'link'
    ];

    /**
     * Update the given LinkItem's link (URL) string. Use a database transaction so operations
     * will automatically rollback if a failure occurs.
     *
     * @param App\Models\Interfaces\iItem $item  The LinkItem to be updated.
     * @param App\Models\Task $task  The Task to which the LinkItem belongs.
     * @param string $content  The new link (URL) string.
     *
     * @return App\Models\LinkItem
     */
    public function updateItem(iItem $item, Task $task, $content)
    {
        return DB::transaction(function () use ($item, $task, $content) {
            $item->link = $content;
            $item->save();

            $list = $task->subcategory->category->taskList;
            ListElement::updateListElement($list, $content, $item->list_element_id);

            return $item;
        });
    }
}

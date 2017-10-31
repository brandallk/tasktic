<?php
/**
 * A comment, sentence, or paragraph provided by the user that adds detail to
 * its parent Task.
 */

namespace App\Models;

use DB;
use App\Models\Item;
use App\Models\ListElement;
use App\Models\Interfaces\iItem;

class DetailItem extends Item
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'detail'
    ];

    /**
     * Update the given DetailItem's detail string. Use a database transaction so operations
     * will automatically rollback if a failure occurs.
     *
     * @param App\Models\Task $task  The Task to which the DetailItem belongs.
     * @param string $content  The new detail string.
     *
     * @return App\Models\DetailItem
     */
    public function updateItem(Task $task, string $content)
    {
        $item = $this;

        return DB::transaction(function () use ($item, $task, $content) {
            $item->detail = $content;
            $item->save();

            // Also update the corresponding ListElement name.
            $list = $task->subcategory->category->taskList;
            ListElement::updateListElement($list, $content, $item->list_element_id);

            return $item;
        });
    }
}

<?php
/**
 * A string provided by the user in any format, representing a date/time, intdended
 * to be used to supply a deadline for the parent Task.
 */

namespace App\Models;

use DB;
use App\Models\Item;
use App\Models\ListElement;
use App\Models\Interfaces\iItem;

class DeadlineItem extends Item
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'deadline'
    ];

    /**
     * Update a DeadlineItem's deadline. Use a database transaction so operations will
     * automatically rollback if a failure occurs.
     *
     * @param App\Models\Task $task  The Task to which the DeadlineItem belongs.
     * @param string $content  The new deadline string.
     *
     * @return App\Models\DeadlineItem
     */
    public function updateItem(Task $task, string $content)
    {
        return DB::transaction(function () use ($task, $content) {
            $this->deadline = $content;
            $this->save();

            // Also update the parent Task's 'deadline' property.
            $task->deadline = $content;
            $task->save();

            // Also update the corresponding ListElement name.
            $list = $task->subcategory->category->taskList;
            ListElement::updateListElement($list, $content, $this->list_element_id);

            return $this;
        });
    }

    /**
     * Delete a DeadlineItem. Null out the parent Task's 'deadline' property, then
     * execute the generic 'deleteItem' method from App\Models\Item.
     *
     * @return bool
     */
    public function deleteItem()
    {
        $this->task->deadline = null;
        $this->task->save();

        return parent::deleteItem();
    }
}

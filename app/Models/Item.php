<?php
/**
 * An abstract class containing methods shared by classes implementing the iItem interface:
 * DeadlineItem, DetailItem, and LinkItem.
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListElement;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\Interfaces\iItem;

abstract class Item extends Model implements iItem
{
    /**
     * Define an Eloquent ORM one-to-many reverse relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  Item belongs-to Task.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Create a new iItem of the given type (DeadlineItem, DetailItem, or LinkItem), assigned to
     * the given Task. Use a database transaction so operations will automatically rollback if a
     * failure occurs.
     *
     * @param App\Models\Task $task  The Task to which the iItem belongs.
     * @param string $type  The type of iItem created: 'deadline', 'detail', or 'link'.
     * @param string $content  The deadline string, detail text, or link URL string that comprises
     * the body of the iItem.
     *
     * @return App\Models\Interfaces\iItem
     */
    public static function newItem(Task $task, string $type, string $content)
    {
        return DB::transaction(function () use ($task, $type, $content) {
            /* A unique 13-character string to distinguish the iItem from other ListElements and
               TaskItems */
            $uniqueID = uniqid();

            $item = self::create([
                'task_id' => $task->id,
                'list_element_id' => $uniqueID,
                'type' => $type,
                $type => $content
            ]);

            $item->task()->associate($task);
            $item->save();

            // Also create a TaskItem on the parent Task corresponding to the new iItem.
            TaskItem::addItem($item, $task);

            // Also create a ListElement on the TaskList corresponding to the new iItem.
            $list = $task->subcategory->category->taskList;
            ListElement::addListElement($list, $type, $content, $uniqueID);

            return $item;
        });
    }

    /**
     * Update the given iItem's content. (This method is implemented by iItem classes.)
     *
     * @param App\Models\Task $task  The Task to which the iItem belongs.
     * @param string $content  The new content.
     *
     * @return App\Models\Interfaces\iItem
     */
    abstract public function updateItem(Task $task, string $content);
    
    /**
     * Delete the given iItem. Use a database transaction so operations will automatically
     * rollback if a failure occurs.
     *
     * @return bool
     */
    public function deleteItem()
    {
        $item = $this;
        $task = $this->task;

        return DB::transaction(function () use ($item, $task) {
            $list = $task->subcategory->category->taskList;
            $uniqueID = $item->list_element_id;

            // Also delete the corresponding TaskItem.
            TaskItem::removeItem($item, $task);

            $item->delete();

            // Also delete the corresponding ListELement.
            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

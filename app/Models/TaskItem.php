<?php
/**
 * A collection listing the type and unique_id of each of a Task's DeadlineItems, DetailItems,
 * and LinkItems. Each TaskItem is distinguished from the others by its unique random-string id
 * (the 'unique_id'), which is also stored on each individual DeadlineItem, DetailItem, and LinkItem
 * instance as its 'list_element_id'.
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Interfaces\iItem;

// Contains a current record of all the corresponding Task's DeadlineItems, DetailItems, and LinkItems
class TaskItem extends Model
{ 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'type', 'unique_id'
    ];

    /**
     * Define an Eloquent ORM one-to-many reverse relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  TaskItem belongs-to Task.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Create a new TaskItem corresponding to the given iItem, assigned to the given Task. Use a
     * database transaction so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\Task $task  The Task to which the TaskItem belongs.
     * @param App\Models\Interfaces\iItem $item  The DeadlineItem, DetailItem, or LinkItem
     * corresponding to this TaskItem
     *
     * @return App\Models\TaskItem
     */
    public static function addItem(iItem $item, Task $task)
    {
        return DB::transaction(function () use ($item, $task) {
            $taskItem = self::create([
                'task_id' => $task->id,
                'type' => $item->type,
                'unique_id' => $item->list_element_id
            ]);

            $taskItem->task()->associate($task);
            $taskItem->save();

            return $taskItem;
        });
    }

    /**
     * Delete a TaskItem corresponding to the given iItem that is assigned to the given Task.
     * Use a database transaction so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\Task $task  The Task to which the TaskItem belongs.
     * @param App\Models\Interfaces\iItem $item  The DeadlineItem, DetailItem, or LinkItem
     * corresponding to this TaskItem
     *
     * @return bool
     */
    public static function removeItem(iItem $item, Task $task)
    {
        $taskItem = $task->taskItems->where('unique_id', $item->list_element_id)->first();

        $taskItem->delete();

        return true;
    }
}

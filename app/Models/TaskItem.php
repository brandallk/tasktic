<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Interfaces\iItem;

// Contains a current record of all the corresponding Task's DeadlineItems, DetailItems, and LinkItems
class TaskItem extends Model
{ 
    protected $fillable = [
        'task_id', 'type', 'unique_id'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

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

    public static function removeItem(iItem $item, Task $task)
    {
        $taskItem = $task->taskItems->where('unique_id', $item->list_element_id)->first();

        $taskItem->delete();
    }
}

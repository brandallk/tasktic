<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListElement;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\Interfaces\iItem;

abstract class Item extends Model implements iItem
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /** @param $content  is either string or text */
    public static function newItem(Task $task, string $type, $content)
    {
        return DB::transaction(function () use ($task, $type, $content) {
            $uniqueID = uniqid();

            $item = self::create([
                'task_id' => $task->id,
                'list_element_id' => $uniqueID,
                'type' => $type,
                $type => $content
            ]);

            $item->task()->associate($task);
            $item->save();

            TaskItem::addItem($item, $task);

            $list = $task->subcategory->category->taskList;
            ListElement::addListElement($list, $type, $content, $uniqueID);

            return $item;
        });
    }

    /** @param $content  is either string or text */
    abstract public function updateItem(iItem $item, Task $task, $content);
    
    public static function deleteItem(iItem $item, Task $task)
    {
        return DB::transaction(function () use ($item, $task) {
            $list = $task->subcategory->category->taskList;
            $uniqueID = $item->list_element_id;

            TaskItem::removeItem($item, $task);

            $item->delete();

            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

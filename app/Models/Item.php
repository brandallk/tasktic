<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ListElement;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\Interfaces\iItem;

abstract class Item extends Model implements iItem
{
    /** @param $content  is either DateTime, string, or text */
    abstract public function updateTask(iItem $item, $content);

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /** @param $content  is either DateTime, string, or text */
    public static function newItem(Task $task, string $type, $content)
    {
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
    }

    /** @param $content  is either DateTime, string, or text */
    public function updateItem(iItem $item, $content)
    {
        $item->content = $content;
        $item->save();

        $this->updateTask($item, $content);

        return $item;
    }

    public static function deleteItem(iItem $item, Task $task)
    {
        $list = $task->subcategory->category->taskList;
        $uniqueID = $item->list_element_id;

        TaskItem::removeItem($item, $task);

        $item->delete();

        ListElement::deleteListElement($list, $uniqueID);
    }
}

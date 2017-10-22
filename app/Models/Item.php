<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Interfaces\iItem;

abstract class Item extends Model implements iItem
{
    abstract public function updateTask(iItem $item, string $content);

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public static function newItem(Task $task, string $type, string $content)
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

        $task->addItem($item);

        $list = $task->subcategory->category->taskList;
        $list->addListElement($type, $content, $uniqueID);

        return $item;
    }

    public function updateItem(iItem $item, string $content)
    {
        $item->content = $content;
        $item->save();

        $this->updateTask($item, $content);

        return $item;
    }

    public static function deleteItem(iItem $item, Task $task)
    {
        $list = $task->subcategory->category->taskList;
        $itemID = $item->list_element_id;

        $task->removeItem($item);

        $item->delete();

        $list->deleteListElement($itemID);
    }
}

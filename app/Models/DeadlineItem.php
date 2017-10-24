<?php

namespace App\Models;

use App\Models\Item;
use App\Models\ListElement;
use App\Models\Interfaces\iItem;

class DeadlineItem extends Item
{
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'deadline'
    ];

    public function updateItem(iItem $item, Task $task, $content)
    {
        $item->deadline = $content;
        $item->save();

        $task->deadline = $content;
        $task->save();

        $list = $task->subcategory->category->taskList;
        ListElement::updateListElement($list, $content, $item->list_element_id);

        return $item;
    }
}

<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Interfaces\iItem;

class DetailItem extends Item
{
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'detail'
    ];

    public function updateItem(iItem $item, Task $task, $content)
    {
        $item->detail = $content;
        $item->save();

        $list = (new TaskList)->where('id', $task->id)->first();

        ListElement::updateListElement($list, $content, $item->list_element_id);

        return $item;
    }
}

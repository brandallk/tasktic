<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Interfaces\iItem;

class LinkItem extends Item
{
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'link'
    ];

    public function updateItem(iItem $item, Task $task, $content)
    {
        $item->link = $content;
        $item->save();

        return $item;
    }
}

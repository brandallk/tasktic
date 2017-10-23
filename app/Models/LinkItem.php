<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Interfaces\iItem;

class LinkItem extends Item
{
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'link'
    ];

    public function updateTask(iItem $item, $content)
    {
        return $item->task;
    }
}

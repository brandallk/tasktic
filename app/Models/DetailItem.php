<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Interfaces\iItem;

class DetailItem extends Item
{
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'detail'
    ];

    public function updateTask(iItem $item, $content)
    {
        return $item->task;
    }
}

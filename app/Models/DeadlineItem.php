<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Interfaces\iItem;

class DeadlineItem extends Item
{
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'deadline'
    ];

    public function updateTask(iItem $item, string $content)
    {
        $task = $item->task;
        $task->deadline = $content;
        $task->save();

        return $task;
    }
}

<?php

namespace App\Models;

use DB;
use App\Models\Item;
use App\Models\ListElement;
use App\Models\Interfaces\iItem;

class DetailItem extends Item
{
    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'detail'
    ];

    public function updateItem(iItem $item, Task $task, $content)
    {
        return DB::transaction(function () use ($item, $task, $content) {
            $item->detail = $content;
            $item->save();

            $list = $task->subcategory->category->taskList;
            ListElement::updateListElement($list, $content, $item->list_element_id);

            return $item;
        });
    }
}

<?php

namespace App\Models\Traits;

use App\Models\Task;
use App\Models\Interfaces\Item;

trait Deletable
{
    public static function deleteItem(Item $item, Task $task)
    {
        $list = $task->subcategory->category->taskList;
        $itemID = $item->list_element_id;

        $task->removeItem($item);

        $item->delete();

        $list->deleteListElement($itemID);
    }
}
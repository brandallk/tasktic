<?php

namespace App\Models\Interfaces;

use App\Models\Task;
use App\Models\Interfaces\iItem;

interface iItem
{
    public function task();

    /** @param $content  is either string or text */
    public static function newItem(Task $task, string $type, string $content);

    /** @param $content  is either string or text */
    public function updateItem(iItem $item, Task $task, string $content);

    public static function deleteItem(iItem $item, Task $task);
}
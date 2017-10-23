<?php

namespace App\Models\Interfaces;

use App\Models\Task;
use App\Models\Interfaces\iItem;

interface iItem
{
    public function task();

    /** @param $content  is either DateTime, string, or text */
    public static function newItem(Task $task, string $type, $content);

    /** @param $content  is either DateTime, string, or text */
    public function updateItem(iItem $item, $content);

    /** @param $content  is either DateTime, string, or text */
    public function updateTask(iItem $item, $content);

    public static function deleteItem(iItem $item, Task $task);
}
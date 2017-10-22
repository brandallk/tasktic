<?php

namespace App\Models\Interfaces;

use App\Models\Task;
use App\Models\Interfaces\iItem;

interface iItem
{
    public function task();

    public static function newItem(Task $task, string $type, string $content);

    public function updateItem(iItem $item, string $content);

    public function updateTask(iItem $item, string $content);

    public static function deleteItem(iItem $item, Task $task);
}
<?php

namespace App\Models\Interfaces;

use App\Models\Task;
use App\Models\Interfaces\Item;

interface Item
{
    public function task();

    public static function newItem(Task $task, string $content);

    public function updateItem(Item $item, string $content);

    public static function deleteItem(Item $item);
}
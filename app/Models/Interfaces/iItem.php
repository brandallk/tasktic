<?php
/**
 * An interface implemented by all DeadlineItems, DetailItems, and LinkItems, which
 * extend the App\Models\Item abstract class.
 */

namespace App\Models\Interfaces;

use App\Models\Task;
use App\Models\Interfaces\iItem;

interface iItem
{
    public function task();

    public static function newItem(Task $task, string $type, string $content);

    public function updateItem(iItem $item, Task $task, string $content);

    public static function deleteItem(iItem $item, Task $task);
}
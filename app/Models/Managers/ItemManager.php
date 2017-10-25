<?php

namespace App\Models\Managers;

use App\Models\Task;
use App\Models\DeadlineItem;
use App\Models\DetailItem;
use App\Models\LinkItem;

class ItemManager
{
    private static function getClassName(string $type)
    {
        $className = null;

        switch ($type) {
            case 'deadline':
                $className = DeadlineItem::class;
                break;
            case 'detail':
                $className = DetailItem::class;
                break;
            case 'link':
                $className = LinkItem::class;
                break;
        }

        return $className;
    }

    /** @param $content  is either string or text */
    public static function newItem(string $type, $content, Task $task)
    {
        $className = self::getClassName($type);

        return $className::newItem($task, $type, $content);
    }

    public static function deleteItem(string $type, string $uniqueID, Task $task)
    {
        $className = self::getClassName($type);

        $item = $className::where('list_element_id', $uniqueID)->first();
        $className::deleteItem($item, $task);
    }
}
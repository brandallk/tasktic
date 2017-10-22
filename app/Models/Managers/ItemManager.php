<?php

namespace App\Models\Managers;

use App\Models\Task;
use App\Models\DeadlineItem;
use App\Models\DetailItem;
use App\Models\LinkItem;

class ItemManager
{
    protected static function item(string $type)
    {
        $itemType = null;

        switch ($type) {
            case 'deadline':
                $itemType = DeadlineItem::class;
                break;
            case 'detail':
                $itemType = DetailItem::class;
                break;
            case 'link':
                $itemType = LinkItem::class;
                break;
        }

        return $itemType;
    }

    /** @param $content  is either DateTime, string, or text */
    public static function newItem(string $type, $content, Task $task)
    {
        $itemType = self::item($type);

        return $itemType::newItem($task, $type, $content);
    }

    public static function deleteItem(string $type, int $uniqueID, Task $task)
    {
        $itemType = self::item($type);

        $item = $itemType::where('list_element_id', $uniqueID)->first();
        $itemType::deleteItem($item, $task);
    }
}
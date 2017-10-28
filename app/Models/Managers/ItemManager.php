<?php
/**
 * A manager class that provides a static factory method for iItem instances
 * (DeadlineItems, DetailItems, and LinkItems) and a corresponding static deleteItem
 * method that can delete any type of iItem instance.
 */

namespace App\Models\Managers;

use App\Models\Task;
use App\Models\DeadlineItem;
use App\Models\DetailItem;
use App\Models\LinkItem;

class ItemManager
{
    /**
     * Switch on the given iItem type to get the corresponding model class name.
     *
     * @param string $type  The type of iItem: 'deadline', 'detail', or 'link'.
     *
     * @return string  Class-name of the given iItem type.
     */
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

    /**
     * Create a new iItem instance of the given type (DeadlineItem, DetailItem, or LinkItem),
     * assigned to the given Task.
     *
     * @param string $type  The type of iItem created: 'deadline', 'detail', or 'link'.
     * @param mixed $content  The deadline string, detail text, or link URL string that comprises
     * the body of the iItem.
     * @param App\Models\Task $task  The Task to which the iItem belongs.
     *
     * @return App\Models\Interfaces\iItem
     */
    public static function newItem(string $type, $content, Task $task)
    {
        $className = self::getClassName($type);

        return $className::newItem($task, $type, $content);
    }

    /**
     * Delete the given iItem instance.
     *
     * @param string $type  The type of iItem created: 'deadline', 'detail', or 'link'.
     * @param string $uniqueID  The iItem's unique 13-character 'list_element_id'.
     * @param App\Models\Task $task  The iItem's parent Task.
     *
     * @return bool
     */
    public static function deleteItem(string $type, string $uniqueID, Task $task)
    {
        $className = self::getClassName($type);

        $item = $className::where('list_element_id', $uniqueID)->first();
        
        return $className::deleteItem($item, $task);
    }
}
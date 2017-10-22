<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\Interfaces\Item;
use App\Models\DeadlineItem;
use App\Models\DetailItem;
use App\Models\LinkItem;
use App\Models\Managers\ItemManager;

class Task extends Model
{
    protected $fillable = [
        'subcategory_id', 'name', 'list_element_id'
    ];

    public $items = [];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function deadlineItem()
    {
        return $this->hasOne(DeadlineItem::class);
    }

    public function detailItems()
    {
        return $this->hasMany(DetailItem::class);
    }

    public function linkItems()
    {
        return $this->hasMany(LinkItem::class);
    }

    public function addItem(Item $item)
    {
        $this->items[] = [
            'type' => $item->type,
            'uniqueID' => $item->list_item_id
        ];
    }

    public function removeItem(Item $item)
    {
        $itemDescription = [
            'type' => $item->type,
            'uniqueID' => $item->list_item_id
        ];

        if (in_array($itemDescription, $this->items)) {
            $key = array_search($itemDescription, $this->items);
            array_splice($this->items, $key, 1);
        }
    }

    public function newTask(Subcategory $subcategory, string $name, string $deadline = null)
    {
        $uniqueID = uniqid();

        $task = self::create([
            'subcategory_id' => $subcategory->id,
            'name' => $name,
            'list_element_id' => $uniqueID,
            'deadline' => $deadline
        ]);

        $task->subcategory()->associate($subcategory);
        $task->save();

        if (!is_null($deadline)) {
            ItemManager::newItem('deadline', $deadline, $task);
        }

        $list = $subcategory->category->taskList;
        $list->addListElement('task', $name, $uniqueID);

        return $task;
    }

    public function updateDetails(Task $task, string $name = null, string $deadline = null)
    {
        if (!is_null($name)) {
            $task->name = $name;
            $task->save();
        }

        if (!is_null($deadline)) {
            $item = $task->deadlineItem;
            $item->updateItem($item, $deadline);
        }

        return $task;
    }

    public function updateStatus(Task $task, string $status)
    {
        $task->status = $status;
        $task->save();

        return $task;
    }

    public static function deleteTask(Task $task)
    {
        $list = $task->subcategory->category->taskList;
        $taskID = $task->list_element_id;

        foreach ($task->items as $item) {
            ItemManager::deleteItem($item->type, $item->uniqueID, $task);
        }

        // Note: important that the Task is deleted AFTER its child Items are deleted
        $task->delete();

        $list->deleteListElement($taskID);
    }
}
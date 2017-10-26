<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListElement;
use App\Models\Subcategory;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\Interfaces\iItem;
use App\Models\DeadlineItem;
use App\Models\DetailItem;
use App\Models\LinkItem;
use App\Models\Managers\ItemManager;

class Task extends Model
{
    protected $fillable = [
        'subcategory_id', 'name', 'list_element_id', 'status', 'deadline'
    ];

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

    public function taskItems()
    {
        return $this->hasMany(TaskItem::class);
    }

    public static function newTask(Subcategory $subcategory, string $name, string $deadline = null)
    {
        return DB::transaction(function () use ($subcategory, $name, $deadline) {
            $uniqueID = uniqid();

            $task = self::create([
                'subcategory_id' => $subcategory->id,
                'name' => $name,
                'list_element_id' => $uniqueID,
                'status' => 'incomplete',
                'deadline' => $deadline
            ]);

            $task->subcategory()->associate($subcategory);
            $task->save();

            if (!is_null($deadline)) {
                ItemManager::newItem('deadline', $deadline, $task);
            }

            $list = $subcategory->category->taskList;
            ListElement::addListElement($list, 'task', $name, $uniqueID);

            return $task;
        });
    }

    public function updateDetails(Task $task, string $name = null, string $deadline = null)
    {
        return DB::transaction(function () use ($task, $name, $deadline) {
            if (!is_null($name)) {
                $task->name = $name;
                $task->save();

                $list = $task->subcategory->category->taskList;
                ListElement::updateListElement($list, $name, $task->list_element_id);
            }

            if (!is_null($deadline)) {
                $item = $task->deadlineItem;
                $item->updateItem($item, $task, $deadline);
            }

            return $task;
        });
    }

    public function updateStatus(Task $task, string $status)
    {
        $task->status = $status;
        $task->save();

        return $task;
    }

    public static function deleteTask(Task $task)
    {
        return DB::transaction(function () use ($task) {
            $list = $task->subcategory->category->taskList;
            $uniqueID = $task->list_element_id;

            foreach ($task->taskItems as $item) {
                ItemManager::deleteItem($item->type, $item->uniqueID, $task);
            }

            // Note: important that the Task is deleted AFTER its child Items are deleted
            $task->delete();

            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

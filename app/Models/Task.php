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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subcategory_id', 'name', 'list_element_id', 'status', 'deadline'
    ];

    /**
     * Define an Eloquent ORM one-to-many reverse relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  Task belongs-to Subcategory.
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship. TaskItems include DeadlineItems,
     * DetailItems, and LinkItems. The TaskItems collection provides a single list of iItem
     * instances that belong to the Task, including their names, types, and uniqueIDs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  Task has-many TaskItems.
     */
    public function taskItems()
    {
        return $this->hasMany(TaskItem::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  Task has-many DeadlineItems.
     */
    public function deadlineItem()
    {
        return $this->hasOne(DeadlineItem::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  Task has-many DetailItems.
     */
    public function detailItems()
    {
        return $this->hasMany(DetailItem::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  Task has-many LinkItems.
     */
    public function linkItems()
    {
        return $this->hasMany(LinkItem::class);
    }

    /**
     * Create a new Task with the given name, assigned to the given Subcategory. Use a database
     * transaction so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\Subcategory $subcategory  The Subcategory to which the Task belongs.
     * @param string $name  The name assigned to the Task.
     * @param string $deadline  Optional string that names a date/time in any user-provided format.
     * NULL by default. If not NULL, it is used as the name of a new-created DeadlineItem assigned
     * to the Task.
     *
     * @return App\Models\Task
     */
    public static function newTask(Subcategory $subcategory, string $name, string $deadline = null)
    {
        return DB::transaction(function () use ($subcategory, $name, $deadline) {
            // A unique 13-character string to distinguish the Task from other ListElements
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

            // Use the ItemManager to create a DeadlineItem assigned to the Task.
            if (!is_null($deadline)) {
                ItemManager::newItem('deadline', $deadline, $task);
            }

            // Also create a ListElement on the TaskList corresponding to the new Task.
            $list = $subcategory->category->taskList;
            ListElement::addListElement($list, 'task', $name, $uniqueID);

            return $task;
        });
    }

    /**
     * Update the given Task's name and/or deadline. Use a database transaction so operations
     * will automatically rollback if a failure occurs.
     *
     * @param string $name  Optional parameter: the Task's new name.
     * @param string $deadline  Optional parameter: the Task's new deadline.
     *
     * @return App\Models\Task
     */
    public function updateDetails(string $name = null, string $deadline = null)
    {
        $task = $this;

        return DB::transaction(function () use ($task, $name, $deadline) {
            if (!is_null($name)) {
                $task->name = $name;
                $task->save();

                // Also update the corresponding ListElement name.
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

    /**
     * Update the given Task's status. The 'status' can be 'incomplete', 'complete', or
     * 'priority'.
     *
     * @param App\Models\Task $task  The Task to be updated.
     * @param string $status  The Task's new status: 'incomplete', 'complete', or 'priority'.
     *
     * @return App\Models\Task
     */
    public function updateStatus(Task $task, string $status)
    {
        $task->status = $status;
        $task->save();

        return $task;
    }

    /**
     * Delete the given Task and all iItem instances belonging to it. Use a database transaction
     * so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\Task $task  The Task to be deleted.
     *
     * @return bool
     */
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

            // Also delete the corresponding ListELement.
            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

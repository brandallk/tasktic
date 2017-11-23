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
        'subcategory_id', 'name', 'list_element_id', 'status', 'deadline', 'display_position'
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
     * Define an Eloquent ORM one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne  Task has-one DeadlineItem.
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
                'deadline' => $deadline,
                'display_position' =>
                    $subcategory->getLastDisplayedTask()->display_position + 1
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
            // If the request includes a new 'name'
            if (!is_null($name)) {
                // Update the old 'name'
                $task->name = $name;
                $task->save();

                // Also update the corresponding ListElement name.
                $list = $task->subcategory->category->taskList;
                ListElement::updateListElement($list, $name, $task->list_element_id);
            }

            // If the request includes a new 'deadline'
            if (!is_null($deadline)) {

                // If there is already a 'deadline' for this Task
                if (!is_null($task->deadlineItem)) {
                    // Update it
                    $task->deadlineItem->updateItem($task, $deadline);
                }

                // If there's no 'deadline' for this Task
                else {
                    // Create one
                    $item = DeadlineItem::newItem($task, 'deadline', $deadline);
                    // And update the Task's matching 'deadline' property
                    $task->deadline = $deadline;
                    $task->save();
                }
            }

            return $task;
        });
    }

    /**
     * Update the given Task's status. The 'status' can be 'incomplete', 'complete', or
     * 'priority'.
     *
     * @param string $status  The Task's new status: 'incomplete', 'complete', or 'priority'.
     *
     * @return App\Models\Task
     */
    public function updateStatus(string $status)
    {
        $this->status = $status;
        $this->save();

        return $this;
    }

    /**
     * Reassign the Task's display_position (when it is dragged-and-dropped to a new position
     * within its Subcategory <div>).
     *
     * @param App\Models\Task $insertSite  The Task immediately next to the drop-target
     * @param bool $insertAbove  True if inserting above the $insertSite
     * @param bool $insertBelow  True if inserting below the $insertSite
     *
     * @return bool  True if the display position changed successfully
     */
    public function changeDisplayPosition(
        Task $insertSite, bool $insertAbove, bool $insertBelow)
    {
        // A Task can't be moved to a different Subcategory by drag-and-drop
        if ($insertSite->subcategory == $this->subcategory) {

            $this->display_position = null; // Temporarily clear the display position
            $insertPosition         = $insertSite->display_position;
            $tasks                  = $this->subcategory->getTasksOrderedByDisplayPosition();

            if ($insertAbove) {

                for ($i=0; $i < count($tasks); $i++) {

                    // For all Tasks other than $this...
                    if ($tasks[$i] != $this) {

                        // Above the insert position, reassign display positions starting from 1
                        if ($tasks[$i]->display_position < $insertPosition) {
                            $tasks[$i]->display_position = $i + 1;
                            $tasks[$i]->save();

                        // At and below the insert position, increment display positions by 1
                        } elseif ($tasks[$i]->display_position >= $insertPosition) {
                            $tasks[$i]->display_position += 1;
                            $tasks[$i]->save();
                        }
                    }                
                }

                // The insert position is $this Task's new display position
                $this->display_position = $insertPosition;
                $this->save();

            } elseif ($insertBelow) {
                
                for ($i=0; $i < count($tasks); $i++) {

                    // For all Tasks other than $this...
                    if ($tasks[$i] != $this) {

                        // Reassign display position starting from 1
                        $tasks[$i]->display_position = $i + 1;
                        $tasks[$i]->save();
                    }                
                }

                // $this Task is positioned below the other Tasks
                $lastTask = $this->subcategory->getLastDisplayedTask();
                $this->display_position = $lastTask->display_position + 1;
                $this->save();

            }

            return true; // $this Task's display position was changed
        }

        return false; // $this Task's display position was not changed
    }

    /**
     * Delete the given Task and all iItem instances belonging to it. Use a database transaction
     * so operations will automatically rollback if a failure occurs.
     *
     * @return bool
     */
    public function deleteTask()
    {
        $task = $this;

        return DB::transaction(function () use ($task) {
            $list = $task->subcategory->category->taskList;
            $uniqueID = $task->list_element_id;

            foreach ($task->taskItems as $item) {
                ItemManager::deleteItem($item->type, $item->unique_id, $task);
            }

            // Note: important that the Task is deleted AFTER its child Items are deleted
            $task->delete();

            // Also delete the corresponding ListELement.
            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

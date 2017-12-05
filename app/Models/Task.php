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
                    ($subcategory->getLastDisplayedTask())
                        ? ($subcategory->getLastDisplayedTask()->display_position + 1) : 1
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
        return DB::transaction(function () use ($name, $deadline) {
            // If the request includes a new 'name'
            if (!is_null($name)) {
                // Update the old 'name'
                $this->name = $name;
                $this->save();

                // Also update the corresponding ListElement name.
                $list = $this->subcategory->category->taskList;
                ListElement::updateListElement($list, $name, $this->list_element_id);
            }

            // If the request includes a new 'deadline'
            if (!is_null($deadline)) {

                // If there is already a 'deadline' for this Task
                if (!is_null($this->deadlineItem)) {
                    // Update it
                    $this->deadlineItem->updateItem($this, $deadline);
                }

                // If there's no 'deadline' for this Task
                else {
                    // Create one
                    $item = DeadlineItem::newItem($this, 'deadline', $deadline);
                    // And update the Task's matching 'deadline' property
                    $this->deadline = $deadline;
                    $this->save();
                }
            }

            return $this;
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
     * within its Subcategory <div>). Use a database transaction so operations will
     * automatically rollback if a failure occurs.
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
        return DB::transaction(function () use (
            $insertSite, $insertAbove, $insertBelow
        ) {        
            // A Task can't be moved to a different Subcategory by drag-and-drop
            if ($insertSite->subcategory != $this->subcategory) {

                return false;

            } else {

                $tasks = $this->subcategory->getTasksOrderedByDisplayPosition();

                // Get the $tasks[i] index corresponding to the insert position:
                // 'display_position's are numbered from 1, but $tasks[i] are indexed from 0
                $insertIndex = array_search($tasks[$insertSite->display_position - 1], $tasks);
                    
                $tasksBeforeInsertPosition = $insertAbove ? array_slice($tasks, 0, $insertIndex) : $tasks;

                $this->display_position = $this->renumberAscendingFromOne(
                    $tasksBeforeInsertPosition,
                    $property = 'display_position',
                    $except = $this
                );
                $this->save();

                if ($insertAbove) {

                    $tasksAfterInsertPosition = array_slice($tasks, $insertIndex);

                    $this->renumberAscendingFromGiven(
                        $tasksAfterInsertPosition,
                        $property = 'display_position',
                        $given = $this->display_position,
                        $except = $this
                    );
                }

                return true;
            }
        });
    }

    /**
     * Renumber numeric values of a given property of the given array's elements, starting
     * from 1. Optionally ignore one given array element during the renumbering.
     *
     * @param array $array
     * @param string $elementProperty  Array element properties to be renumbered
     * @param mixed $ignoredElement  This element will not be included in the renumbering
     *
     * @return int  Value of the element property for the next element after the final one
     * if the array continued with additional element(s) after the final one 
     */
    private function renumberAscendingFromOne(
        array $array, string $elementProperty, $ignoredElement = null)
    {
        if (count($array) >= 1) { // If the array is not empty

            $j = 1;
            for ($i=0; $i < count($array); $i++) {

                // For all array elements other than $ignoredElement...
                if ($array[$i]->id != $ignoredElement->id) {

                    // Renumber the given property in ascending integers starting from 1
                    $array[$i]->$elementProperty = $j++;
                    $array[$i]->save();
                }                
            }

            $lastElement = $array[count($array) - 1];
            $nextPropInSequence = $lastElement->$elementProperty + 1;

            return $nextPropInSequence;

        } else { // If the array is empty

            return $firstPropInSequence = 1;
        }
    }

    /**
     * Renumber numeric values of a given property of the given array's elements, starting
     * from a given number. Optionally ignore one given array element during the renumbering.
     *
     * @param array $array
     * @param string $elementProperty  Array element properties to be renumbered
     * @param int $given  The number from which to begin renumbering
     * @param mixed $ignoredElement  This element will not be included in the renumbering
     *
     * @return bool
     */
    private function renumberAscendingFromGiven(
        array $array, string $elementProperty, int $given, $ignoredElement = null)
    {
        for ($i=0; $i < count($array); $i++) {

            // For all array elements other than $ignoredElement...
            if ($array[$i]->id != $ignoredElement->id) {

                // ...increment the given property by 1
                $array[$i]->$elementProperty = ++$given;
                $array[$i]->save();
            }                
        }

        return true;
    }

    /**
     * Delete the given Task and all iItem instances belonging to it. Use a database transaction
     * so operations will automatically rollback if a failure occurs.
     *
     * @return bool
     */
    public function deleteTask()
    {
        return DB::transaction(function () {
            $list = $this->subcategory->category->taskList;
            $uniqueID = $this->list_element_id;

            foreach ($this->taskItems as $item) {
                ItemManager::deleteItem($item->type, $item->unique_id, $this);
            }

            // Note: important that the Task is deleted AFTER its child Items are deleted
            $this->delete();

            // Also delete the corresponding ListELement.
            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

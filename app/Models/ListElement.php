<?php
/**
 * A collection listing the name, type, and unique_id of each of a TaskList's Categories,
 * Subcategories, Tasks, DeadlineItems, DetailItems, and LinkItems. Each ListElement is
 * distinguished from the others by its unique random-string id (the 'unique_id'), which is
 * also stored on each individual Category, Subcategory, Task, and iItem instance as its
 * 'list_element_id'.
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;

class ListElement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_list_id', 'unique_id', 'type', 'name'
    ];

    /**
     * Define an Eloquent ORM one-to-many reverse relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  ListElement belongs-to TaskList.
     */
    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    /**
     * Create a new ListElement with the given name, type, and unique_id, assigned to the given
     * TaskList. Use a database transaction so operations will automatically rollback if a failure
     * occurs.
     *
     * @param App\Models\TaskList $list  The TaskList to which the ListELement belongs.
     * @param string $type  Is 'category', 'subcategory', 'task', 'deadline', 'detail', or 'link'.
     * @param mixed $name  The name assigned to the ListElement: either a string or NULL.
     * @param string $uniqueID  A unique 13-character string.
     *
     * @return App\Models\ListElement
     */
    public static function addListElement(TaskList $list, string $type, $name, string $uniqueID)
    {
        return DB::transaction(function () use ($list, $type, $name, $uniqueID) {
            $element = self::create([
                'task_list_id' => $list->id,
                'unique_id' => $uniqueID,
                'type' => $type,
                'name' => $name
            ]);

            $element->taskList()->associate($list);
            $element->save();

            return $element;
        });
    }

    /**
     * Update a ListElement's name.
     *
     * @param App\Models\TaskList $list  The TaskList to which the ListELement belongs.
     * @param mixed $name  The ListElement's new name.
     * @param string $uniqueID  The ListElement's unique 13-character string.
     *
     * @return App\Models\ListElement
     */
    public static function updateListElement(TaskList $list, $name, string $uniqueID)
    {
        $element = $list->listElements->where('unique_id', $uniqueID)->first();

        $element->name = $name;
        $element->save();

        return $element;
    }

    /**
     * Delete a ListElement.
     *
     * @param App\Models\TaskList $list  The TaskList to which the ListELement belongs.
     * @param string $uniqueID  The ListElement's unique 13-character string.
     *
     * @return bool
     */
    public static function deleteListElement(TaskList $list, string $uniqueID)
    {
        $element = $list->listElements->where('unique_id', $uniqueID)->first();

        $element->delete();

        return true;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;

class TaskList extends Model
{
    protected $fillable = [
        'user_id', 'name', 'saved', 'autodelete'
    ];

    public $elements = []; // make this a hasOne Eloquent relationship & add a migration for 'list_elements' table with 'id', 'task_list_id', 'unique_id', 'type', and 'name'. Create a class ListElement with $fillables, taskList(), etc.

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public static function newDefaultTaskList(User $user)
    {
        // Get a string for the current day's date formatted like "Wednesday, October 18th"
        $today = (\Carbon\Carbon::now())->format('l\, F jS');

        // Create a new unsaved TaskList using today's date as 'name'
        return self::newTaskList($user, $today, false);
    }

    public static function newTaskList(User $user, string $name, bool $saved = true)
    {
        $list = self::create([
            'user_id' => $user->id,
            'name' => $name,
            'saved' => $saved,
            'autodelete' => true,
        ]);

        $list->user()->associate($user);
        $list->save();

        Category::newDefaultCategory($list);

        return $list;
    }

    /** @param $name  is either a string or null */
    public function addListElement(string $type, $name, string $uniqueID)
    {
        // Remake this as ListElement::addListElement(TaskList $list, string $type, $name, string $uniqueID). Pull in the TaskList and add columns 'unique_id', 'type', and 'name' to TaskList->elements
        $listElement = [
            'type' => $type,
            'name' => $name,
        ];

        $this->elements[$uniqueID] = $listElement;
    }

    public function updateTaskList(TaskList $list, string $name)
    {
        $list->name = $name;
        $list->save();

        return $list;
    }

    public function setDefaultName(TaskList $list)
    {
        if (!$list->saved) {
            // Get a string for the current day's date formatted like "Wednesday, October 18th"
            $defaultName = (\Carbon\Carbon::now())->format('l\, F jS');
            $this->updateTaskList($list, $defaultName);
            return true;
        }

        return false;
    }

    public function deleteListElement(string $listElementID)
    {
        // Remake this as ListElement::deleteListElement(TaskList $list, string $uniqueID)
        if (array_key_exists($listElementID, $this->elements)) {
            unset($this->elements[$listElementID]);
        }
    }

    public static function deleteTaskList(TaskList $list)
    {
        foreach ($list->categories as $category) {
            Category::deleteCategory($category);
        }

        // Note: important that the TaskList is deleted AFTER its child Categories are deleted
        $list->delete();
    }

    public function updateLastTimeLoaded(TaskList $list)
    {
        $now = (\Carbon\Carbon::now())->toDateTimeString();

        $list->last_time_loaded = $now;
        $list->save();

        return $list;
    }
}

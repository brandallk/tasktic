<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TaskList;
use App\Models\ListElement;
use App\Models\Category;

class TaskList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'saved', 'autodelete'
    ];

    /**
     * Define an Eloquent ORM one-to-many reverse relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  TaskList belongs-to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship. ListElements include Categories, Subcategories,
     * Tasks, DeadlineItems, DetailItems, and LinkItems. The ListElements collection provides a single
     * list of objects that belong to the TaskList, including their names and uniqueIDs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  TaskList has-many ListElements.
     */
    public function listElements()
    {
        return $this->hasMany(ListElement::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  TaskList has-many Categories.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Create a new unsaved TaskList that takes the current date as name.
     *
     * @param App\Models\User $user
     *
     * @return App\Models\TaskList
     */
    public static function newDefaultTaskList(User $user)
    {
        // Get a string for the current day's date formatted like "Wednesday, October 18th"
        $today = (\Carbon\Carbon::now())->format('l\, F jS');

        return self::newTaskList($user, $today, false);
    }

    /**
     * Create a new TaskList with the given name, assigned to the given User. Use a database
     * transaction so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\User $user  The User to which the TaskList belongs.
     * @param string $name  The name assigned to the TaskList.
     * @param bool $saved  Indicates whether the TaskList is 'saved' or not. Equals TRUE by default.
     *
     * @return App\Models\TaskList
     */
    public static function newTaskList(User $user, string $name, bool $saved = true)
    {
        return DB::transaction(function () use ($user, $name, $saved) {
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
        });
    }

    /**
     * Update a TaskList's name.
     *
     * @param App\Models\TaskList $list  The TaskList to be updated.
     * @param string $name  The new name assigned to the TaskList.
     *
     * @return App\Models\TaskList
     */
    public function updateTaskList(TaskList $list, string $name)
    {
        $list->name = $name;
        $list->save();

        return $list;
    }

    /**
     * Update an unsaved TaskList's name to the current date and return TRUE. If the TaskList
     * is already 'saved', do nothing and return FALSE.
     *
     * @param App\Models\TaskList $list  The TaskList to be updated.
     *
     * @return bool
     */
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

    /**
     * Update the given TaskList's 'last_time_loaded' property to the current DateTime.
     *
     * @param App\Models\TaskList $list  The TaskList to be updated.
     *
     * @return void
     */
    public function updateLastTimeLoaded(TaskList $list)
    {
        $now = (\Carbon\Carbon::now())->toDateTimeString();

        $list->last_time_loaded = $now;
        $list->save();

        return $list;
    }

    /**
     * Delete the given TaskList and all Categories belonging to it. Use a database transaction
     * so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\TaskList $list  The TaskList to be deleted.
     *
     * @return bool  TRUE
     */
    public static function deleteTaskList(TaskList $list)
    {
        return DB::transaction(function () use ($list) {
            foreach ($list->categories as $category) {
                Category::deleteCategory($category);
            }

            // Note: important that the TaskList is deleted AFTER its child Categories are deleted
            $list->delete();

            return true;
        });
    }
}

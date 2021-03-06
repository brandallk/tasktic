<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
        'user_id', 'name', 'saved', 'last_time_loaded', 'autodelete'
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
     * list of object instances that belong to the TaskList, including their names and uniqueIDs.
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
     * Get all tasks belonging to this TaskList that have 'priority' status.
     *
     * @return array  Array of App\Models\Task instances
     */
    public function priorities()
    {
        $priorityTasks = [];

        foreach ($this->categories as $category) {
            foreach ($category->subcategories as $subcategory) {
                foreach ($subcategory->tasks as $task) {
                    if ($task->status == 'priority') {
                        $priorityTasks[] = $task;
                    }
                }
            }
        }

        return $priorityTasks;
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
        $today = (\Carbon\Carbon::now())->timezone($user->timezone)->format('l\, F jS');

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
                'last_time_loaded' => (\Carbon\Carbon::now())->toDateTimeString(),
                'autodelete' => true,
            ]);

            $list->user()->associate($user);
            $list->save();

            Category::newDefaultCategory($list);

            return $list;
        });
    }

    /**
     * Update a TaskList's name. Use a database transaction so operations will
     * automatically rollback if a failure occurs.
     *
     * @param string $name  The new name assigned to the TaskList.
     *
     * @return App\Models\TaskList
     */
    public function updateTaskList(string $name)
    {
        return DB::transaction(function () use ($name) {
            $this->name = $name;
            $this->save();

            $isDefaultList = ($this->id == $this->user->getDefaultList()->id);

            if ($isDefaultList) {
                $this->saved = true;
                $this->save();

                self::newDefaultTaskList($this->user);
            }

            return $this;
        });
    }

    /**
     * Update an unsaved TaskList's name to the current date and return TRUE. If the TaskList
     * is already 'saved', do nothing and return FALSE.
     *
     * @return bool
     */
    public function resetNameByDate()
    {
        return DB::transaction(function () {
            if (!$this->saved) {
                // Get a string for the current day's date formatted like "Wednesday, October 18th"
                $defaultName = (\Carbon\Carbon::now())->timezone($this->user->timezone)->format('l\, F jS');

                $this->name = $defaultName;
                $this->save();

                return $this;
            }

            return false;
        });
    }

    /**
     * Update the given TaskList's 'last_time_loaded' property to the current DateTime.
     *
     * @return void
     */
    public function updateLastTimeLoaded()
    {
        $now = (\Carbon\Carbon::now())->toDateTimeString();

        $this->last_time_loaded = $now;
        $this->save();

        return $this;
    }

    /**
     * Delete the given TaskList and all Categories belonging to it. Use a database transaction
     * so operations will automatically rollback if a failure occurs.
     *
     * @return bool
     */
    public function deleteTaskList()
    {
        return DB::transaction(function () {
            $isDefaultList = ($this->id == $this->user->getDefaultList()->id);

            foreach ($this->categories as $category) {
                $category->deleteCategory();
            }

            // Note: important that the TaskList is deleted AFTER its child Categories are deleted
            $this->delete();

            $noListsLeft = $this->user->taskLists()->get()->isEmpty();

            if ($isDefaultList || $noListsLeft) {
                self::newDefaultTaskList($this->user);
            }

            return true;
        });
    }
}

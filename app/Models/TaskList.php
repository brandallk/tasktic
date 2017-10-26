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
    protected $fillable = [
        'user_id', 'name', 'saved', 'autodelete'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function listElements()
    {
        return $this->hasMany(ListElement::class);
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

    public function updateLastTimeLoaded(TaskList $list)
    {
        $now = (\Carbon\Carbon::now())->toDateTimeString();

        $list->last_time_loaded = $now;
        $list->save();

        return $list;
    }
}

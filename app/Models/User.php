<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\TaskList;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Define an Eloquent ORM one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  User has-many TaskLists.
     */
    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }

    /**
     * Get the TaskList that the User most-recently loaded.
     *
     * @return App\Models\TaskList  The User's TaskList that has the most-recent 'last_time_loaded' property.
     */
    public function getCurrentList()
    {
        $lists = $this->taskLists;

        $lastLoaded = $lists->sortBy('last_time_loaded')->last();

        return $lastLoaded;
    }

    /**
     * Get the User's default TaskList.
     *
     * @return App\Models\TaskList  The User's default TaskList.
     */
    public function getDefaultList()
    {
        $lists = $this->taskLists;

        $default = $lists->where('saved', false)->first();

        return $default;
    }

    /**
     * Assign a local timezone (by converting local timezone offset from UTC) to the User
     *
     * @param int $tzOffsetMinutes  The number of minutes the User's local timezone is
     * offset from UTC (can be 0 or negative)
     *
     * @return bool
     */
    public function setTimezone(int $tzOffsetMinutes)
    {
        $tzOffsetSeconds = ($tzOffsetMinutes)*60;

        $tzName = timezone_name_from_abbr("", $tzOffsetSeconds, false);

        $this->timezone = $tzName;
        $this->save();

        return true;
    }
}

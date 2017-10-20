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
     * Eloquent relationship: User has-many TaskLists.
     *
     */
    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }

    /**
     * Get the TaskList that the User most-recently loaded.
     *
     */
    public function getCurrentList()
    {
        $lists = $this->taskLists;

        $lastLoaded = $lists->sortBy('last_time_loaded')->last();

        return $lastLoaded;
    }
}

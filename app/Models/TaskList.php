<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\User;

class TaskList extends Model
{
    protected $fillable = [
        'user_id', 'name'
    ];

    public function newDefaultTaskList(User $user)
    {
        // Get a string for today's date like "Wednesday, October 18th"
        $todayString = (\Carbon\Carbon::now())->format('l\, F jS');

        // Create a new unsaved TaskList using today's date as the name 
        $defaultList = self::create([
            'user_id' => $user->id,
            'name' => $todayString
        ]);

        // Category->newDefaultCategory($List)
    }
}

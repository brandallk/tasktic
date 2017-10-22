<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;

class ListElement extends Model
{
    protected $fillable = [
        'task_list_id', 'unique_id', 'type', 'name'
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    /** @param $name  is either a string or null */
    public static function addListElement(TaskList $list, string $type, $name, string $uniqueID)
    {
        $element = self::create([
            'task_list_id' => $list->id, // shouldn't need this if I associate with $list below
            'unique_id' => $uniqueID,
            'type' => $type,
            'name' => $name
        ]);

        $element->taskList()->associate($list);
        $element->save();

        return $element;
    }

    public static function deleteListElement(TaskList $list, string $uniqueID)
    {
        $element = $list->listElements->where('unique_id', $uniqueID)->first();

        $element->delete();
    }
}

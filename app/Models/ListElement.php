<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;

/* Contains a current record of all the corresponding TaskList's Categories, Subcategories, Tasks, and task Items.
 * Each such ListElement is distinguished from the others belonging to the TaskList via a unique random-string id,
 * its 'unique_id', which is also stored on each individual Category, Subcategory, Task, and iItem model as its
 * 'list_element_id'. */
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
            'task_list_id' => $list->id,
            'unique_id' => $uniqueID,
            'type' => $type,
            'name' => $name
        ]);

        $element->taskList()->associate($list);
        $element->save();

        return $element;
    }

    public static function updateListElement(TaskList $list, $name, string $uniqueID)
    {
        $element = $list->listElements->where('unique_id', $uniqueID)->first();

        $element->name = $name;
        $element->save();

        return $element;
    }

    public static function deleteListElement(TaskList $list, string $uniqueID)
    {
        $element = $list->listElements->where('unique_id', $uniqueID)->first();

        $element->delete();
    }
}

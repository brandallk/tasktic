<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;

class Subcategory extends Model
{
    protected $fillable = [
        'category_id', 'name', 'list_element_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public static function newDefaultSubcategory(Category $category)
    {
        // Create a new Subcategory with 'name' == null
        $defaultSubcategory = self::newSubcategory($category, null);

        return $defaultSubcategory;
    }

    /** @param $name  is either a string or null */
    public static function newSubcategory(Category $category, $name)
    {
        $uniqueID = uniqid();

        $subcategory = self::create([
            'category_id' => $category->id,
            'name' => $name,
            'list_element_id' => $uniqueID,
        ]);

        $subcategory->category()->associate($category);
        $subcategory->save();

        $category->taskList->addListElement('subcategory', $name, $uniqueID);

        return $subcategory;
    }

    public function updateSubcategory(Subcategory $subcategory, string $name)
    {
        $subcategory->name = $name;
        $subcategory->save();

        return $subcategory;
    }

    public static function deleteSubcategory(Subcategory $subcategory)
    {
        $list = $subcategory->category->taskList;

        $subcategory->delete();

        foreach ($subcategory->tasks as $task) {
            Task::deleteTask($task);
        }

        $list->deleteListElement($subcategory->list_element_id);
    }
}

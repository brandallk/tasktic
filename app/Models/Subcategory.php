<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListElement;
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
        return DB::transaction(function () use ($category) {
            // Create a new Subcategory with 'name' == null
            $defaultSubcategory = self::newSubcategory($category, null);

            return $defaultSubcategory;
        });
    }

    /** @param $name  is either a string or null */
    public static function newSubcategory(Category $category, $name)
    {
        return DB::transaction(function () use ($category, $name) {
            $uniqueID = uniqid();

            $subcategory = self::create([
                'category_id' => $category->id,
                'name' => $name,
                'list_element_id' => $uniqueID,
            ]);

            $subcategory->category()->associate($category);
            $subcategory->save();

            $list = $category->taskList;
            ListElement::addListElement($list, 'subcategory', $name, $uniqueID);

            return $subcategory;
        });
    }

    public function updateSubcategory(Subcategory $subcategory, string $name)
    {
        return DB::transaction(function () use ($subcategory, $name) {
            $subcategory->name = $name;
            $subcategory->save();

            $list = $subcategory->category->taskList;
            ListElement::updateListElement($list, $name, $subcategory->list_element_id);

            return $subcategory;
        });
    }

    public static function deleteSubcategory(Subcategory $subcategory)
    {
        return DB::transaction(function () use ($subcategory) {
            $list = $subcategory->category->taskList;
            $uniqueID = $subcategory->list_element_id;

            foreach ($subcategory->tasks as $task) {
                Task::deleteTask($task);
            }

            // Note: important that the Subcategory is deleted AFTER its child Tasks are deleted
            $subcategory->delete();

            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

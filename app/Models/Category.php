<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;

class Category extends Model
{
    protected $fillable = [
        'task_list_id', 'name', 'list_element_id'
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public static function newDefaultCategory(TaskList $list)
    {
        // Create a new Category with 'name' == null
        $defaultCategory = self::newCategory($list, null);

        Subcategory::newDefaultSubcategory($defaultCategory);

        return $defaultCategory;
    }

    /** @param $name  is either a string or null */
    public static function newCategory(TaskList $list, $name)
    {
        $uniqueID = uniqid();

        $category = self::create([
            'task_list_id' => $list->id,
            'name' => $name,
            'list_element_id' => $uniqueID,
        ]);

        $category->taskList()->associate($list);
        $category->save();

        $list->addListElement('category', $name, $uniqueID);

        return $category;
    }

    public function updateCategory(Category $category, string $name)
    {
        $category->name = $name;
        $category->save();

        return $category;
    }

    public static function deleteCategory(Category $category)
    {
        $taskList = $category->taskList;

        $category->delete();

        foreach ($category->subcategories as $subcategory) {
            Subcategory::deleteSubcategory($subcategory);
        }

        $taskList->deleteListElement($category->list_element_id);
    }
}
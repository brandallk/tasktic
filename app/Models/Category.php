<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskList;
use App\Models\ListElement;
use App\Models\Category;
use App\Models\Subcategory;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_list_id', 'name', 'list_element_id'
    ];

    /**
     * Define an Eloquent ORM one-to-many reverse relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  Category belongs-to TaskList.
     */
    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  Category has-many Subcategories.
     */
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    /**
     * Create a new Category with name === NULL, with a Subcategory also named NULL, assigned to
     * the given TaskList. Use a database transaction so operations will automatically rollback if
     * a failure occurs.
     *
     * @param App\Models\TaskList $list  The TaskList to which the Category belongs.
     *
     * @return App\Models\Category
     */
    public static function newDefaultCategory(TaskList $list)
    {
        return DB::transaction(function () use ($list) {
            
            $defaultCategory = self::newCategory($list, null);

            Subcategory::newDefaultSubcategory($defaultCategory);

            return $defaultCategory;
        });
    }

    /**
     * Create a new Category with the given name, assigned to the given TaskList. Use a database
     * transaction so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\TaskList $list  The TaskList to which the Category belongs.
     * @param mixed $name  The name assigned to the Category: either a string or NULL.
     *
     * @return App\Models\Category
     */
    public static function newCategory(TaskList $list, $name)
    {
        return DB::transaction(function () use ($list, $name) {
            // A unique 13-character string to distinguish the Category from other ListElements
            $uniqueID = uniqid();

            $category = self::create([
                'task_list_id' => $list->id,
                'name' => $name,
                'list_element_id' => $uniqueID,
            ]);

            $category->taskList()->associate($list);
            $category->save();

            // Also create a ListElement on the TaskList corresponding to the new Category.
            ListElement::addListElement($list, 'category', $name, $uniqueID);

            return $category;
        });
    }

    /**
     * Update the given Category's name. Use a database transaction so operations will
     * automatically rollback if a failure occurs.
     *
     * @param mixed $name  The Category's new name.
     *
     * @return App\Models\Category
     */
    public function updateCategory($name)
    {
        $category = $this;

        return DB::transaction(function () use ($category, $name) {
            $category->name = $name;
            $category->save();

            // Also update the corresponding ListElement name.
            $list = $category->taskList;
            ListElement::updateListElement($list, $name, $category->list_element_id);

            return $category;
        });
    }

    /**
     * Delete the given Category and all Subcategories belonging to it. Use a database transaction
     * so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\Category $category  The Category to be deleted.
     *
     * @return bool
     */
    public static function deleteCategory(Category $category)
    {
        return DB::transaction(function () use ($category) {
            $list = $category->taskList;
            $uniqueID = $category->list_element_id;

            foreach ($category->subcategories as $subcategory) {
                Subcategory::deleteSubcategory($subcategory);
            }

            // Note: important that the Category is deleted AFTER its child Subcategories are deleted
            $category->delete();

            // Also delete the corresponding ListELement.
            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

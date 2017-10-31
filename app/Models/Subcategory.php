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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'name', 'list_element_id'
    ];

    /**
     * Define an Eloquent ORM one-to-many reverse relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo  Subcategory belongs-to Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define an Eloquent ORM one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  Subcategory has-many Tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Create a new Subcategory with name === NULL, assigned to the given Category. Use a database
     * transaction so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\Category $category  The Category to which the Subcategory belongs.
     *
     * @return App\Models\Subcategory
     */
    public static function newDefaultSubcategory(Category $category)
    {
        return DB::transaction(function () use ($category) {
            
            $defaultSubcategory = self::newSubcategory($category, null);

            return $defaultSubcategory;
        });
    }

    /**
     * Create a new Subcategory with the given name, assigned to the given Category. Use a database
     * transaction so operations will automatically rollback if a failure occurs.
     *
     * @param App\Models\Category $category  The Category to which the Subcategory belongs.
     * @param mixed $name  The name assigned to the Subcategory: either a string or NULL.
     *
     * @return App\Models\Subcategory
     */
    public static function newSubcategory(Category $category, $name)
    {
        return DB::transaction(function () use ($category, $name) {
            // A unique 13-character string to distinguish the Subcategory from other ListElements
            $uniqueID = uniqid();

            $subcategory = self::create([
                'category_id' => $category->id,
                'name' => $name,
                'list_element_id' => $uniqueID,
            ]);

            $subcategory->category()->associate($category);
            $subcategory->save();

            // Also create a ListElement on the TaskList corresponding to the new Subcategory.
            $list = $category->taskList;
            ListElement::addListElement($list, 'subcategory', $name, $uniqueID);

            return $subcategory;
        });
    }

    /**
     * Update the given Subcategory's name. Use a database transaction so operations will
     * automatically rollback if a failure occurs.
     *
     * @param mixed $name  The Subcategory's new name.
     *
     * @return App\Models\Subcategory
     */
    public function updateSubcategory(string $name)
    {
        $subcategory = $this;

        return DB::transaction(function () use ($subcategory, $name) {
            $subcategory->name = $name;
            $subcategory->save();

            // Also update the corresponding ListElement name.
            $list = $subcategory->category->taskList;
            ListElement::updateListElement($list, $name, $subcategory->list_element_id);

            return $subcategory;
        });
    }

    /**
     * Delete the given Subcategory and all Tasks belonging to it. Use a database transaction
     * so operations will automatically rollback if a failure occurs.
     *
     * @return bool
     */
    public function deleteSubcategory()
    {
        $subcategory = $this;

        return DB::transaction(function () use ($subcategory) {
            $list = $subcategory->category->taskList;
            $uniqueID = $subcategory->list_element_id;

            foreach ($subcategory->tasks as $task) {
                Task::deleteTask($task);
            }

            // Note: important that the Subcategory is deleted AFTER its child Tasks are deleted
            $subcategory->delete();

            // Also delete the corresponding ListELement.
            ListElement::deleteListElement($list, $uniqueID);

            return true;
        });
    }
}

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
        //
    }

    public function tasks()
    {
        //
    }

    public static function newDefaultSubcategory(Category $category)
    {
        //
    }

    /** @param $name  is either a string or null */
    public static function newSubcategory(Category $category, $name)
    {
        //
    }

    public function updateSubcategory(Subcategory $subcategory, string $name)
    {
        //
    }

    public static function deleteSubcategory(Subcategory $subcategory)
    {
        //
    }
}

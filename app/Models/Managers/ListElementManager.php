<?php

namespace App\Models\Managers;

use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;

/*  */
class ListElementManager
{
    private static function getBuilder(string $type)
    {
        $builderFunc = null;

        switch ($type) {
            case 'category':
                $builderFunc = 'buildCategory';
                break;
            case 'subcategory':
                $builderFunc = 'buildSubcategory';
                break;
            case 'task':
                $builderFunc = 'buildTask';
                break;
        }

        return $builderFunc;
    }

    public static function newListElement(string $elementType, string $name, TaskList $list)
    {
        $builderFunc = self::getBuilder($elementType);

        $element = self::$builderFunc($name, $list);

        return $element;
    }

    private static function buildCategory(string $name, TaskList $list)
    {
        return Category::newCategory($list, $name);
    }

    private static function buildSubcategory(string $name, TaskList $list)
    {
        $category = Category::where('task_list_id', $list->id)->where('name', null)->first();
        return Subcategory::newSubcategory($category, $name);
    }

    private static function buildTask(string $name, TaskList $list)
    {
        $category = Category::where('task_list_id', $list->id)->where('name', null)->first();
        $subcategory = Subcategory::where('category_id', $category->id)->where('name', null)->first();
        return Task::newTask($subcategory, $name);
    }
}
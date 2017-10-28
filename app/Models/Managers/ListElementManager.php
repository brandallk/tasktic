<?php
/**
 * A manager class that provides a static factory method for Category, Subcategory,
 * or Task instances belonging to a given TaskList instance.
 */

namespace App\Models\Managers;

use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;

/*  */
class ListElementManager
{
    /**
     * Switch on the given list-element type ('category', 'subcategory', or 'task') to get
     * the name of a corresponding builder method that constructs an instance of that type.
     *
     * @param string $type  The type of list-element: 'category', 'subcategory', or 'task'.
     *
     * @return string  Name of the builder method.
     */
    private static function getBuilder(string $type)
    {
        $builder = null;

        switch ($type) {
            case 'category':
                $builder = 'buildCategory';
                break;
            case 'subcategory':
                $builder = 'buildSubcategory';
                break;
            case 'task':
                $builder = 'buildTask';
                break;
        }

        return $builder;
    }

    /**
     * Factory method: Create a new instance of the given type (Category, Subcategory, or Task),
     * assigned to the given TaskList, by calling the appropriate builder method below.
     *
     * @param string $elementType  The type of list-element created: 'category', 'subcategory',
     * or 'task'.
     * @param string $name  The name assigned to the new list-element.
     * @param App\Models\TaskList $list  The TaskList to which the list-element belongs.
     * @param string $deadline  Optional string that names a date/time if a Task instance is being created.
     *
     * @return mixed  App\Models\Category, App\Models\Subcategory, or App\Models\Task
     */
    public static function newListElement(string $elementType, string $name, TaskList $list, string $deadline = null)
    {
        $builder = self::getBuilder($elementType);

        $element = self::$builder($name, $list, $deadline);

        return $element;
    }

    /**
     * Builder method: Create a new Category instance.
     *
     * @param string $name  The name assigned to the new Category.
     * @param App\Models\TaskList $list  The TaskList to which the Category belongs.
     * @param string $deadline  (Not used by this method.)
     *
     * @return App\Models\Category
     */
    private static function buildCategory(string $name, TaskList $list, string $deadline = null)
    {
        return Category::newCategory($list, $name);
    }

    /**
     * Builder method: Create a new Subcategory instance. Assign it to the parent TaskList's
     * default NULL-name Category.
     *
     * @param string $name  The name assigned to the new Subcategory.
     * @param App\Models\TaskList $list  The TaskList to which the Subcategory belongs.
     * @param string $deadline  (Not used by this method.)
     *
     * @return App\Models\Subcategory
     */
    private static function buildSubcategory(string $name, TaskList $list, string $deadline = null)
    {
        $category = Category::where('task_list_id', $list->id)->where('name', null)->first();
        return Subcategory::newSubcategory($category, $name);
    }

    /**
     * Builder method: Create a new Task instance. Assign it to the parent TaskList's
     * default NULL-name Category, and to that Category's default NULL-name Subcategory.
     *
     * @param string $name  The name assigned to the new Task.
     * @param App\Models\TaskList $list  The TaskList to which the Task belongs.
     * @param string $deadline  Optional string that names a date/time in any user-provided format.
     *
     * @return App\Models\Task
     */
    private static function buildTask(string $name, TaskList $list, string $deadline = null)
    {
        $category = Category::where('task_list_id', $list->id)->where('name', null)->first();
        $subcategory = Subcategory::where('category_id', $category->id)->where('name', null)->first();
        return Task::newTask($subcategory, $name, $deadline);
    }
}
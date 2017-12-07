<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Create a new Category instance and show it.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'currentListID' => 'required|integer'
        ]);

        $store = function($args) {
            extract($args);

            $list = TaskList::find($request->currentListID);
            $name = $request->name;

            Category::newCategory($list, $name);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $store,
            $args = ['request' => $request]
        );
    }

    /**
     * Update the given Category.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $update = function($args) {
            extract($args);

            $list = $category->taskList;
            $name = $request->name;

            $category->updateCategory($name);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $update,
            $args = ['request' => $request , 'category' => $category]
        );
    }

    /**
     * Delete the given Category.
     *
     * @param App\Models\Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        $destroy = function($args) {
            extract($args);

            $list = $category->taskList;

            $category->deleteCategory();

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $destroy,
            $args = ['category' => $category]
        );
    }
}

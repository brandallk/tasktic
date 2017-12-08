<?php

namespace App\Http\Controllers;

use Throwable;
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

        try {

            $list = TaskList::find($request->currentListID);
            $name = $request->name;

            Category::newCategory($list, $name);

            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
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

        try {

            $list = $category->taskList;
            $name = $request->name;

            $category->updateCategory($name);

            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
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
        try {

            $list = $category->taskList;

            $category->deleteCategory();

            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
    }
}

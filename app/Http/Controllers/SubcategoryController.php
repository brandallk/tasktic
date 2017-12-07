<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    /**
     * Create a new Subcategory instance and show it.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'categoryID' => 'required|integer'
        ]);

        $store = function($args) {
            extract($args);

            $category = Category::find($request->categoryID);
            $list     = $category->taskList;
            $name     = $request->name;

            Subcategory::newSubcategory($category, $name);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $store,
            $args = ['request' => $request]
        );
    }

    /**
     * Update the given Subcategory.
     *
     * @param Illuminate\Http\Request $request
     * @param App\Models\Subcategory $subcategory
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $update = function($args) {
            extract($args);

            $list = $subcategory->category->taskList;
            $name = $request->name;

            $subcategory->updateSubcategory($name);

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $update,
            $args = ['request' => $request, 'subcategory' => $subcategory]
        );
    }

    /**
     * Delete the given Subcategory.
     *
     * @param App\Models\Subcategory $subcategory
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        $destroy = function($args) {
            extract($args);

            $list = $subcategory->category->taskList;

            $subcategory->deleteSubcategory();

            return redirect()->route('lists.show', ['list' => $list->id]);
        };

        return $this->tryOrCatch(
            $destroy,
            $args = ['subcategory' => $subcategory]
        );
    }
}

<?php

namespace App\Http\Controllers;

use Throwable;
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

        try {

            $category = Category::find($request->categoryID);
            $list     = $category->taskList;
            $name     = $request->name;

            Subcategory::newSubcategory($category, $name);

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

        try {

            $list = $subcategory->category->taskList;
            $name = $request->name;

            $subcategory->updateSubcategory($name);

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
     * Delete the given Subcategory.
     *
     * @param App\Models\Subcategory $subcategory
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        try {

            $list = $subcategory->category->taskList;

            $subcategory->deleteSubcategory();

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

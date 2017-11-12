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
            'name' => 'required|string',
            'categoryID' => 'required|integer'
        ]);

        try {
            $category = Category::find($request->categoryID);
            $list = $category->taskList;
            $name = $request->name;

            Subcategory::newSubcategory($category, $name);

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
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

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
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

            // PRG pattern: After post request, return redirect to a get request
            // so browser refresh will not resubmit the same post request.
            return redirect()->route('lists.show', ['list' => $list->id]);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }
}

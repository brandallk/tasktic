<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Return the 'list.show' view.
     *
     * @param App\Models\TaskList $list
     *
     * @return \Illuminate\Http\Response
     */
    private function showListView(TaskList $list)
    {
        $data = [
            'user' => Auth::user(),
            'list' => $list
        ];

        return view('list.show', $data);
    }

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
            'name' => 'required|string',
            'currentListID' => 'required|integer'
        ]);

        try {
            $list = TaskList::find($request->currentListID);
            $name = $request->name;

            Category::newCategory($list, $name);

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
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

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
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

            return $this->showListView($list);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }
}

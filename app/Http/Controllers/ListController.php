<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;

class ListController extends Controller
{
    /**
     * Show an index of the all the user's (default and) saved TaskLists.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = [
                'lists' => Auth::user()->taskLists
            ];

            return view('list.index', $data);

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }

    /**
     * Create a new TaskList instance and show it.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        try {
            $user = Auth::user();
            $name = $request->name;

            $newList = TaskList::newTaskList($user, $name);

            return $this->show($newList); // test that response is the list.show view (after show method is added)

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();            
        }
    }
}

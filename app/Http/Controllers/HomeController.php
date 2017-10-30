<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard for an 'admin' user. Redirect a 'visitor' user
     * to his/her most recently-loaded (or default) list.
     *
     * @return \Illuminate\Http\Response
     */
    public function showHome()
    {
        try {
            $user = Auth::user();
            $role = $user->role;

        } catch (\Throwable $e) {
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }

        if ($role == 'admin') {
            return view('admin.dashboard');

        } elseif ($role == 'visitor') {
            try {
                $currentList = $user->getCurrentList();

                $resetName = $currentList->resetNameByDate($currentList);
                if ($resetName) {
                    $currentList = $resetName;
                }

                $routeParams = ['list' => $currentList->id];
                return redirect()->route('lists.show', $routeParams);

            } catch (\Throwable $e) {
                return redirect()->back();
            } catch (\Exception $e) {
                return redirect()->back();
            }

        } else {
            return redirect()->back();
        }
    }
}

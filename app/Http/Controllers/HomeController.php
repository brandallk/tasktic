<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
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
                // Ensure the default list is named according to the current date
                $defaultList = $user->getDefaultList();
                $defaultList->resetNameByDate();

                // Get the user's last-loaded list so it can be displayed
                $currentList = $user->getCurrentList();

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

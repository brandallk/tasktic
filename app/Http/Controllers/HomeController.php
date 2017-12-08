<?php

namespace App\Http\Controllers;

use Throwable;
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

            if ($role == 'admin') {

                return view('admin.dashboard');

            } elseif ($role == 'visitor') {

                // Update default list name with current date
                $defaultList = $user->getDefaultList();
                $defaultList->resetNameByDate();

                // Get user's last-loaded list
                $currentList = $user->getCurrentList();

                return redirect()->route(
                    'lists.show', ['list' => $currentList->id]
                );

            } else {

                return redirect()->back();
            }

        } catch (Throwable $e) {

            if ($this->appEnvironment == 'production') {
                return $this->catchInProduction($e);
            } else {
                return $this->catchLocally($e);
            }
        }
    }
}

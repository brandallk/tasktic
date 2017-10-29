<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showWelcome()
    {
        return view('welcome');
    }
}

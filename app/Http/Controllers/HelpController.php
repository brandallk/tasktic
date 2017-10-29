<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Show the help page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showHelp()
    {
        return view('help');
    }
}

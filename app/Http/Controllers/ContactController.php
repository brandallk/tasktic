<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show the contact page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showContact()
    {
        return view('contact');
    }
}

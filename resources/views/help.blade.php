@extends('layouts.app')

@section('pageTitle')
    How to Use Tasktic
@endsection

@section('nav')
    <nav>
        <ul>
            <li><a href="{{ route('welcome') }}">Welcome Page</a></li>
            <li><a href="{{ route('about') }}">About Tasktic</a></li>
            <li><a href="{{ route('contact') }}">Contact Me</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <h1>How to Use Tasktic</h1>

    <div>
        <p>A few paragraphs and pictures (annotated screenshots) explaining Tasktic's
            features and how to use them.
        </p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
    </div>

@endsection

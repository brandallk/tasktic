@extends('layouts.app')

@section('pageTitle')
    About Tasktic
@endsection

@section('nav')
    <nav>
        <ul>
            <li><a href="{{ route('welcome') }}">Welcome Page</a></li>
            <li><a href="{{ route('help') }}">Help</a></li>
            <li><a href="{{ route('contact') }}">Contact Me</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <section>
        <h1>About Tasktic</h1>
        <p>A few paragraphs on what the Tasktic app is.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
    </section>

    <section>
        <h2>How Tasktic was Created</h2>
        <p>A few more paragraphs on how Tasktic was made and why.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
    </section>

    <footer>
        <ul>
            <li><a href="#">Github Repo</a></li>
            <li><a href="#">BenjamenKing.com</a></li>
            <li><a href="{{ route('contact') }}">Contact Me</a></li>
        </ul>
    </footer>

@endsection

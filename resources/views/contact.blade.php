@extends('layouts.app')

@section('pageTitle')
    Contact Me
@endsection

@section('nav')
    <nav>
        <ul>
            <li><a href="{{ route('welcome') }}">Welcome Page</a></li>
            <li><a href="{{ route('about') }}">About Tasktic</a></li>
            <li><a href="{{ route('contact') }}">Help</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <h1>Contact Me</h1>

    <form method="POST" action="#">
        {{ csrf_field() }}
        <label>Your email: <input type="text" name="email" placeholder="you@youremail.com"></label>
        <textarea name="message" placeholder="Your message"></textarea>
        <button type="submit" name="submit">Send</button>
    </form>

@endsection

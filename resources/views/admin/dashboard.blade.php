@extends('layouts.admin')

@section('pageTitle')
    Tasktic Admin Dashboard
@endsection

@section('nav')
    <nav>
        <ul>
            <li><a href="{{ route('welcome') }}">Welcome Page</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <h1>Tasktic Admin Dashboard</h1>

    <span>You must be the admin. Welcome to your admin dashboard!</span>

@endsection

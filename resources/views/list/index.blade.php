@extends('layouts.app')

@section('pageTitle')
    List Index
@endsection

@section('nav')
    <nav>
        <ul>
            <li><a href="{{ route('welcome') }}">Welcome Page</a></li>
            <li><a href="{{ route('about') }}">About Tasktic</a></li>
            <li><a href="{{ route('help') }}">Help</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <h1>List Index</h1>

    <ul>
        @foreach ($lists as $list)
            <li>
                <a href="{{ route('lists.show', ['list' => $list->id]) }}">
                    {{ $list->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection

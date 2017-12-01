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

    <ul class="taskListIndex">
        @foreach ($lists as $list)
            <li>
                <a href="{{ route('lists.show', ['list' => $list->id]) }}">
                    {{ $list->name }}
                </a>

                <span class="deleteListButton">
                    <i class="fa fa-times-circle action-icon"></i>
                </span>

                @include('list.partials.list.delete')

            </li>
        @endforeach
    </ul>

@endsection

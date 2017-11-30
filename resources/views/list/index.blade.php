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
                <form class="deleteTaskList" method="post" action="{{ route('lists.destroy', ['list' => $list->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <i class="fa fa-times-circle action-icon" aria-hidden="true" onclick="this.parentElement.submit()"></i>
                </form>
            </li>
        @endforeach
    </ul>

@endsection

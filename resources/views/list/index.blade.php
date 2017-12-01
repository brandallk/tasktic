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

                <div class="hidden short modal delete taskList">
                    <div class="shadow-back">
                        <form method="post" action="{{ route('lists.destroy', ['list' => $list->id]) }}">
                            {{ csrf_field() }}

                            {{ method_field('DELETE') }}

                            <div class="heading-wrapper">
                                <span class="modal-heading">
                                    Delete the {{ $list->name }} list?
                                </span>
                            </div>

                            <div class="inputs-wrapper">
                                <div class="message">
                                    <p>
                                        ...and everything in it!
                                    </p>
                                </div>

                                <div class="form-buttons">
                                    <span class="cancel btn white">No</span>
                                    <span class="submit btn pink">Yes</span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>


                <!-- <form class="deleteTaskList" method="post" action="{{ route('lists.destroy', ['list' => $list->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <i class="fa fa-times-circle action-icon" aria-hidden="true" onclick="this.parentElement.submit()"></i>
                </form> -->
            </li>
        @endforeach
    </ul>

@endsection

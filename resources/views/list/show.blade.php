<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $list->name }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Main Menu -->
    <div class="main-menu hidden">
        <ul>
            <li class="action save">Save</li>
            <li class="action new">New</li>
            <li class="action load">Load
                <ul class="dropdown hidden">
                    @foreach ($user->taskLists as $taskList)
                        <li>
                            <a href="{{ route('lists.show', [$taskList->id]) }}">
                                {{ $taskList->name }}
                            </a>
                        </li>
                    @endforeach
                        <li>
                            <a href="{{ route('lists.index') }}">
                                Show All
                            </a>
                        </li>
                </ul>
            </li>
            <li class="action logout">Logout</li>
        </ul>        
    </div>
    <!-- Main-Menu Toggle Button -->
    <span class="menu-toggle closed">{menu-toggle icon}</span>

    <!-- Title Area -->
    <div class="title">
        <h1>{{ $list->name }}</h1>
        <button type="button" name="priorities">Priorities</button>
    </div>

    <!-- Action Menu -->
    <div class="action-menu">
        <ul>
            <li class="action-button create">{create icon}</li>
            <li class="action-button delete">{delete icon}</li>
            <li class="action-button edit">{edit icon}</li>
            <li class="action-button status">{status icon}</li>
            <li class="action-button priority">{priority icon}</li>
        </ul>
    </div>

    <!-- The List -->
    <main>
        @if ($list->categories)
        @foreach ($list->categories as $category)

            <div id="{{ $category->list_element_id }}" class="category">
                <span>{{ $category->name }}</span>

                @if ($category->subcategories)
                @foreach ($category->subcategories as $subcategory)

                    <div id="{{ $subcategory->list_element_id }}" class="subcategory">
                        <span>{{ $subcategory->name }}</span>

                        @if ($subcategory->tasks)
                        @foreach ($subcategory->tasks as $task)

                            <div id="{{ $task->list_element_id }}" class="task">
                                <span>{{ $task->name }}</span>

                                @if ($task->deadlineItem)
                                    <div id="{{ $task->deadlineItem->list_element_id }}" class="deadline">
                                        <span>{alarm-clock icon}</span>
                                        <span>{{ $task->deadlineItem->deadline }}</span>
                                    </div>
                                @endif

                                @if ($task->linkItems)
                                @foreach ($task->linkItems as $link)
                                    <div id="{{ $link->list_element_id }}" class="link">
                                        <span>{{ $link->link }}</span>
                                    </div>
                                @endforeach
                                @endif

                                @if ($task->detailItems)
                                @foreach ($task->detailItems as $detail)
                                    <div id="{{ $detail->list_element_id }}" class="detail">
                                        <span>{{ $detail->detail }}</span>
                                    </div>
                                @endforeach
                                @endif
                                
                            </div>

                        @endforeach
                        @endif

                    </div>

                @endforeach
                @endif

            </div>

        @endforeach
        @endif

        <!-- Add-A-Task Button -->
        <button type="button" name="add-task">Add A Task</button>

    </main>

    <!-- Hidden element for testing purposes -->
    <span style="display:none">last_time_loaded == {{ $list->last_time_loaded }}</span>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

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

    <!-- Main Menu (Top Menu) -->
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
            <li class="action logout">
                <form method="post" action="{{ route('logout') }}">
                    {{ csrf_field() }}
                    <input type="submit" value="Logout">
                </form>
            </li>
        </ul>

        @include('list.partials.list.create')
        @include('list.partials.list.save')

    </div>
    <!-- Main-Menu Toggle Button -->
    <span class="menu-toggle closed">{menu-toggle icon}</span>

    <!-- List-Title Area -->
    <div class="title">
        <h1>{{ $list->name }}</h1>
        <button type="button" name="priorities">Priorities</button>
    </div>

    <!-- Action Menu (Secondary Menu) -->
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

        <!-- Validation Error Messages Display -->
        @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- List categories -->
        @if ($list->categories)
        @foreach ($list->categories as $category)

            <div id="{{ $category->list_element_id }}" class="selectable category">
                <span>{{ $category->name }}</span>

                <!-- List subcategories -->
                @if ($category->subcategories)
                @foreach ($category->subcategories as $subcategory)

                    <div id="{{ $subcategory->list_element_id }}" class="selectable subcategory">
                        <span>{{ $subcategory->name }}</span>

                        <!-- List tasks -->
                        @if ($subcategory->tasks)
                        @foreach ($subcategory->tasks as $task)

                            <div id="{{ $task->list_element_id }}" class="selectable task">
                                <span>{{ $task->name }}</span>

                                <!-- Deadline Items -->
                                @if ($task->deadlineItem)

                                    <div id="{{ $task->deadlineItem->list_element_id }}" class="selectable deadline <?php
                                        if ($task->status == 'priority') {echo 'priority';}
                                        elseif ($task->status == 'complete') {echo 'complete';}
                                        else {echo 'incomplete';}
                                    ?>">
                                        <span>{alarm-clock icon}</span>
                                        <span>{{ $task->deadlineItem->deadline }}</span>
                                    </div>

                                    @include('list.partials.item.deadline.delete')

                                @endif

                                <!-- Link Items -->
                                @if ($task->linkItems)
                                @foreach ($task->linkItems as $link)

                                    <div id="{{ $link->list_element_id }}" class="selectable link">
                                        <span>{{ $link->link }}</span>
                                    </div>

                                    @include('list.partials.item.link.edit')
                                    @include('list.partials.item.link.delete')

                                @endforeach
                                @endif

                                <!-- Detail Items -->
                                @if ($task->detailItems)
                                @foreach ($task->detailItems as $detail)

                                    <div id="{{ $detail->list_element_id }}" class="selectable detail">
                                        <span>{{ $detail->detail }}</span>
                                    </div>

                                    @include('list.partials.item.detail.edit')
                                    @include('list.partials.item.detail.delete')

                                @endforeach
                                @endif

                                @include('list.partials.item.create')
                                @include('list.partials.task.edit.details')
                                @include('list.partials.task.edit.status')
                                @include('list.partials.task.edit.priority')
                                @include('list.partials.task.delete')
                                
                            </div> <!-- end Tasks div -->

                        @endforeach
                        @endif

                        @include('list.partials.task.create')
                        @include('list.partials.subcategory.edit')
                        @include('list.partials.subcategory.delete')

                    </div> <!-- end Subcategories div -->

                @endforeach
                @endif

                @include('list.partials.subcategory.create')
                @include('list.partials.category.edit')
                @include('list.partials.category.delete')

            </div> <!-- end Categories div -->

        @endforeach
        @endif

        @include('list.partials.category.create')

        <!-- Add-A-Task Button -->
        <button type="button" name="add-task">Add A Task</button>
        @include('list.partials.listElement.create')

    </main>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

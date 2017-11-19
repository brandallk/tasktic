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
<body class="list-view">

    <!-- Main Menu (Top Menu) -->
    <div class="main-menu up">
        <ul class="menu-list">
            <li class="action save">Save
            </li><li class="action new">New
            </li><li class="action load">Load
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
            
            </li><li class="action logout">
                Logout
                <form method="post" action="{{ route('logout') }}">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>

        @include('list.partials.list.create')
        @include('list.partials.list.save')

    </div>
    <!-- Main-Menu Toggle Button -->
    <span class="menu-toggle down">
        <i class="fa fa-caret-down fa-2x" aria-hidden="true"></i>
    </span>

    <!-- List-Title Area -->
    <div class="title clearfix">
        <h1>{{ $list->name }}</h1>
        <span class="priorities btn orange">
            <a href="{{ route('lists.priorities', [$taskList->id]) }}">
                Priorities
            </a>
        </span>
    </div>

    <main class="list-view">
        <!-- The List -->
        <div class="theList">

            <!-- Validation Error Messages -->
            @if ($errors->any())
                @include('list.partials.errors.validation')
            @endif

            <!-- List categories -->
            @if ($list->categories)
            @foreach ($list->categories->sortBy('id') as $category)

                <div id="{{ $category->list_element_id }}" class="category selectable <?php
                    if (is_null($category->name)) { echo "no-display"; } ?>">
                    <span class="category-title">{{ $category->name }}</span>

                    <!-- List subcategories -->
                    @if ($category->subcategories)
                    @foreach ($category->subcategories->sortBy('id') as $subcategory)

                        <div id="{{ $subcategory->list_element_id }}" class="subcategory selectable <?php
                            if (is_null($subcategory->name)) { echo "no-display"; } ?>">
                            <span class="subcategory-title">{{ $subcategory->name }}</span>

                            <!-- List tasks -->
                            @if ($subcategory->tasks)
                            @foreach ($subcategory->tasks->sortBy('id') as $task)

                                <div id="{{ $task->list_element_id }}" class="task selectable <?php
                                    if (is_null($subcategory->name)) { echo "null-cat "; }
                                    if ($task->status == 'priority') {echo 'priority';}
                                    elseif ($task->status == 'complete') {echo 'complete';}
                                    else {echo 'incomplete';}
                                    ?>">
                                    <canvas class="task-border top-border hidden" width="0" height="0"></canvas>

                                    <span class="task-toggle down">
                                        @if ($task->taskItems->first())
                                            <i class="fa fa-caret-down fa-2x"></i>
                                        @else
                                            <i class="fa fa-caret-down fa-2x" style="opacity:0" aria-hidden="true"></i>
                                        @endif
                                    </span>

                                    <span class="task-title">{{ $task->name }}</span>
                                    <canvas class="task-border bottom-border hidden" width="0" height="0"></canvas>

                                    <!-- Task Items -->
                                    <div class="task-item-wrapper">

                                        <!-- Deadline Items -->
                                        @if ($task->deadlineItem)

                                            <div id="{{ $task->deadlineItem->list_element_id }}" class="deadline task-item selectable hidden <?php
                                                if ($task->status == 'priority') {echo 'priority';}
                                                elseif ($task->status == 'complete') {echo 'complete';}
                                                else {echo 'incomplete';}
                                            ?>">
                                                <span class="deadline-icon">
                                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                </span>
                                                <span class="task-item-heading deadline-content">{{ $task->deadlineItem->deadline }}</span>

                                                @include('list.partials.item.deadline.delete')
                                            </div>

                                        @endif

                                        <!-- Link Items -->
                                        @if ($task->linkItems)
                                        @foreach ($task->linkItems->sortBy('id') as $link)

                                            <div id="{{ $link->list_element_id }}" class="link task-item selectable hidden">
                                                <span class="task-item-heading link-content">{{ $link->link }}</span>

                                                @include('list.partials.item.link.visitQuery')
                                                @include('list.partials.item.link.edit')
                                                @include('list.partials.item.link.delete')
                                            </div>

                                        @endforeach
                                        @endif

                                        <!-- Detail Items -->
                                        @if ($task->detailItems)
                                        @foreach ($task->detailItems->sortBy('id') as $detail)

                                            <div id="{{ $detail->list_element_id }}" class="detail task-item selectable hidden">
                                                <span class="task-item-heading detail-content">{{ $detail->detail }}</span>

                                                @include('list.partials.item.detail.edit')
                                                @include('list.partials.item.detail.delete')
                                            </div>

                                        @endforeach
                                        @endif

                                    </div> <!-- end Task Items div -->

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

            <!-- Add-A-Task Button -->
            <span class="add-listElement btn pink">Add to list</span>
            @include('list.partials.listElement.create')

        </div> <!-- end theList div -->

        <!-- Action Menu (Secondary Menu) -->
        <div class="action-menu">
            <ul>
                <li class="create action-button">
                    <i class="fa fa-plus-circle fa-3x action-icon" aria-hidden="true"></i>
                </li>
                <li class="delete action-button">
                    <i class="fa fa-times-circle fa-3x action-icon" aria-hidden="true"></i>
                </li>
                <li class="edit action-button">
                    <i class="fa fa-pencil fa-3x action-icon" aria-hidden="true"></i>
                </li>
                <li class="status action-button">
                    <i class="fa fa-check-circle fa-3x action-icon" aria-hidden="true"></i>
                </li>
                <li class="priority action-button">
                    <i class="fa fa-star fa-3x action-icon" aria-hidden="true"></i>
                </li>
            </ul>
        </div>

    </main>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $list->name }} Priorities</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="priorities-view">

    <div class="priorities header clearfix">
        <span class="return icon">
            <a href="{{ route('lists.show', [$list->id]) }}">
                <i class="fa fa-caret-left fa-2x" aria-hidden="true"></i>
            </a>
        </span>

        <div class="titles">
            <h1>Priorities</h1>
            <span class="list-name">{{ $list->name }}</span>
        </div>
    </div>

    <div class="theList">
        
        <!-- List tasks -->
        @if ($priorities)
        @foreach ($priorities as $task)

            <div class="task selectable">
                <span class="task-toggle down">
                    @if ($task->taskItems->first())
                        <i class="fa fa-caret-down fa-2x"></i>
                    @else
                        <i class="fa fa-caret-down fa-2x" style="opacity:0" aria-hidden="true"></i>
                    @endif
                </span>

                <span class="task-title">{{ $task->name }}</span>
                
                <!-- Task Items -->
                <div class="task-item-wrapper">

                    <!-- Deadline Items -->
                    @if ($task->deadlineItem)

                        <div class="deadline task-item selectable hidden">
                            <span class="deadline-icon">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                            </span>
                            <span class="task-item-heading deadline-content">{{ $task->deadlineItem->deadline }}</span>
                        </div>

                    @endif

                    <!-- Link Items -->
                    @if ($task->linkItems)
                    @foreach ($task->linkItems->sortBy('id') as $link)

                        <div class="link task-item selectable hidden">
                            <span class="task-item-heading link-content">{{ $link->link }}</span>
                        </div>

                    @endforeach
                    @endif

                    <!-- Detail Items -->
                    @if ($task->detailItems)
                    @foreach ($task->detailItems->sortBy('id') as $detail)

                        <div class="detail task-item selectable hidden">
                            <span class="task-item-heading detail-content">{{ $detail->detail }}</span>
                        </div>

                    @endforeach
                    @endif

                </div> <!-- end Task Items div -->
                
            </div> <!-- end Tasks div -->

        @endforeach
        @endif

    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    
</body>
</html>
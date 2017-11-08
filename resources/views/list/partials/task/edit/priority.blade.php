<div class="hidden modal priority task">
    <form method="post" action="{{ route('tasks.update.priority', ['task' => $task->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <input type="hidden" name="status" value="<?php
            if ($task->status == 'incomplete' || $task->status == 'complete') {
                echo "priority";
            } elseif ($task->status == 'priority') {
                echo "incomplete";
            }
        ?>">
        
    </form>
</div>
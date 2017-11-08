<div class="hidden modal status task">
    <form method="post" action="{{ route('tasks.update.status', ['task' => $task->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <input type="hidden" name="status" value="<?php
            if ($task->status == 'incomplete' || $task->status == 'priority') {
                echo "complete";
            } elseif ($task->status == 'complete') {
                echo "incomplete";
            }
        ?>">
        
    </form>
</div>

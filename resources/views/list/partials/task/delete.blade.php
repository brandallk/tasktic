<div class="hidden modal delete">
    <form method="post" action="{{ route('tasks.destroy', ['task' => $task->id]) }}">
        {{ csrf_field() }}

        {{ method_field('DELETE') }}

        <span class="modal-heading">
            Delete the task (and everything in it)
        </span>
        
        <input type="submit" value="submit">
    </form>
</div>
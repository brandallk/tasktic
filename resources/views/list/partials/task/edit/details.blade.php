<div class="hidden modal edit-details">
    <form method="post" action="{{ route('tasks.update.details', ['task' => $task->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <span class="modal-heading">
            Change the task name and/or deadline
        </span>

        <label>Name:
            <input type="text" name="name">
        </label>

        <label>Deadline:
            <input type="text" name="deadline">
        </label>
        
        <input type="submit" value="submit">
    </form>
</div>
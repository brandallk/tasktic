<div class="hidden modal create">
    <form method="post" action="{{ route('items.store') }}">
        {{ csrf_field() }}

        <span class="modal-heading">
            Add a new task detail or url-link
        </span>

        <select name="type">
            <option selected="selected" value="detail">detail</option>
            <option value="link">link</option>
        </select>

        <label>Content:
            <input type="text" name="content">
        </label>

        <input type="hidden" name="taskID" value="{{ $task->id }}">
        
        <input type="submit" value="submit">
    </form>
</div>
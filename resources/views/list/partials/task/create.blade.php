<div class="hidden modal create">
    <form method="post" action="{{ route('tasks.store') }}">
        {{ csrf_field() }}

        <span class="modal-heading">
            Add a new task
        </span>

        <label>Name:
            <input type="text" name="name">
        </label>

        <label>Deadline (optional):
            <input type="text" name="deadline">
        </label>

        <input type="hidden" name="subcategoryID" value="{{ $subcategory->id }}">
        
        <input type="submit" value="submit">
    </form>
</div>
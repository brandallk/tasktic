<div class="hidden modal create-listElement">
    <form method="post" action="{{ route('lists.create.element', ['list' => $list->id]) }}">
        {{ csrf_field() }}

        <span class="modal-heading">
            Add to the list
        </span>

        <label>New list element:
            <select name="elementType" size="1">
                <option selected="selected" value="task">Task</option>
                <option value="subcategory">Task Subcategory</option>
                <option value="category">Task Category</option>
            </select>
        </label>

        <label>Name:
            <input type="text" name="name">
        </label>

        <label>Deadline (optional):
            <input type="text" name="deadline">
        </label>

        <input type="submit" value="submit">
    </form>
</div>
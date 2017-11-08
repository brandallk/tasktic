<div class="hidden modal create category">
    <form method="post" action="{{ route('categories.store') }}">
        {{ csrf_field() }}

        <span class="modal-heading">
            Add a new category
        </span>

        <label>Name:
            <input type="text" name="name">
        </label>

        <input type="hidden" name="currentListID" value="{{ $list->id }}">
        
        <input type="submit" value="submit">
    </form>
</div>
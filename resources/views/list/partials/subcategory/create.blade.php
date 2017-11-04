<div class="hidden modal create">
    <form method="post" action="{{ route('subcategories.store') }}">
        {{ csrf_field() }}

        <span class="modal-heading">
            Add a new subcategory
        </span>

        <label>Name:
            <input type="text" name="name">
        </label>

        <input type="hidden" name="categoryID" value="{{ $category->id }}">
        
        <input type="submit" value="submit">
    </form>
</div>
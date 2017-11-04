<div class="hidden modal edit">
    <form method="post" action="{{ route('categories.update', ['category' => $category->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <span class="modal-heading">
            Change the category name
        </span>

        <label>Name:
            <input type="text" name="name">
        </label>
        
        <input type="submit" value="submit">
    </form>
</div>
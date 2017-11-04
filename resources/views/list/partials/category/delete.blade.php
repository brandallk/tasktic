<div class="hidden modal delete">
    <form method="post" action="{{ route('categories.destroy', ['category' => $category->id]) }}">
        {{ csrf_field() }}

        {{ method_field('DELETE') }}

        <span class="modal-heading">
            Delete the category (and everything in it)
        </span>
        
        <input type="submit" value="submit">
    </form>
</div>
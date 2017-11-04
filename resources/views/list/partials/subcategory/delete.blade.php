<div class="hidden modal delete">
    <form method="post" action="{{ route('subcategories.destroy', ['subcategory' => $subcategory->id]) }}">
        {{ csrf_field() }}

        {{ method_field('DELETE') }}

        <span class="modal-heading">
            Delete the subcategory (and everything in it)
        </span>
        
        <input type="submit" value="submit">
    </form>
</div>
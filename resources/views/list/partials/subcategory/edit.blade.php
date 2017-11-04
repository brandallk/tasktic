<div class="hidden modal edit">
    <form method="post" action="{{ route('subcategories.update', ['subcategory' => $subcategory->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <span class="modal-heading">
            Change the subcategory name
        </span>

        <label>Name:
            <input type="text" name="name">
        </label>
        
        <input type="submit" value="submit">
    </form>
</div>
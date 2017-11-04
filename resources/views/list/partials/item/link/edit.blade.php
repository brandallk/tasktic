<div class="hidden modal edit-link">
    <form method="post" action="{{ route('items.update.link', ['item' => $link->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <span class="modal-heading">
            Change the url
        </span>

        <label>New url:
            <input type="text" name="content">
        </label>
        
        <input type="submit" value="submit">
    </form>
</div>
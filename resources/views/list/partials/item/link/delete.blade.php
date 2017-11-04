<div class="hidden modal link-delete">
    <form method="post" action="{{ route('items.destroy.link', ['item' => $link->id]) }}">
        {{ csrf_field() }}

        {{ method_field('DELETE') }}

        <span class="modal-heading">
            Delete this link url
        </span>
        
        <input type="submit" value="submit">
    </form>
</div>
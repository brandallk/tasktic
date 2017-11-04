<div class="hidden modal detail-delete">
    <form method="post" action="{{ route('items.destroy.detail', ['item' => $detail->id]) }}">
        {{ csrf_field() }}

        {{ method_field('DELETE') }}

        <span class="modal-heading">
            Delete this task detail
        </span>
        
        <input type="submit" value="submit">
    </form>
</div>
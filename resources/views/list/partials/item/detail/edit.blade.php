<div class="hidden modal edit-detail">
    <form method="post" action="{{ route('items.update.detail', ['item' => $detail->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <span class="modal-heading">
            Change the task detail content
        </span>

        <label>Content:
            <input type="text" name="content">
        </label>
        
        <input type="submit" value="submit">
    </form>
</div>
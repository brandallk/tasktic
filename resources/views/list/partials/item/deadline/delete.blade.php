<div class="hidden modal deadline-delete">
    <form method="post" action="{{ route('items.destroy.deadine', ['item' => $task->deadlineItem->id]) }}">
        {{ csrf_field() }}

        {{ method_field('DELETE') }}

        <span class="modal-heading">
            Delete the deadline
        </span>
        
        <input type="submit" value="submit">
    </form>
</div>
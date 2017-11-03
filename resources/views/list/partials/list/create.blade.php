<div class="hidden modal new">
    <form method="post" action="{{ route('lists.store') }}">
        {{ csrf_field() }}
        
        <span class="modal-heading">
            Create a new list
        </span>

        <label>Name:
        <input type="text" name="name">
        </label>

        <input type="submit" value="submit">
    </form>
</div>

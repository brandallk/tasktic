<div class="hidden modal save">
    <form method="post" action="{{ route('lists.update', ['list' => $list->id]) }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <span class="modal-heading">
            Save this list as...
        </span>

        <label>Name:
        <input type="text" name="name">
        </label>

        <input type="submit" value="submit">
    </form>
</div>
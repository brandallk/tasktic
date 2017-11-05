<div class="hidden modal main-menu save">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.update', ['list' => $list->id]) }}">
            {{ csrf_field() }}
            
            {{ method_field('PATCH') }}

            <span class="modal-heading">
                Save this list as...
            </span>

            <label>List Name:
                <input type="text" name="name">
            </label>

            <div class="form-buttons">
                <span class="cancel btn white">Cancel</span>
                <span class="submit btn pink">Submit</span>
            </div>
        </form>
    </div>
</div>

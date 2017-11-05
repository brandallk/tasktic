<div class="hidden modal main-menu new">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.store') }}">
            {{ csrf_field() }}
            
            <span class="modal-heading">
                Create a new list
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

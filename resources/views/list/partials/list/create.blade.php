<div class="hidden short modal main-menu new">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.store') }}">
            {{ csrf_field() }}
            
            <div class="heading-wrapper">
                <span class="modal-heading">
                    Create a new list
                </span>
            </div>

            <div class="inputs-wrapper">
                <div class="first input">
                    <label for="list-create-name">Name:</label>
                    <input id="list-create-name" type="text" name="name">
                </div>

                <div class="form-buttons">
                    <span class="cancel btn white">Cancel</span>
                    <span class="submit btn pink">Submit</span>
                </div>
            </div>

            <div class="action icons">
                    <span class="create icon active">
                        <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                    </span>
                    <span class="delete icon hidden">
                        <i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
                    </span>
                    <span class="edit icon hidden">
                        <i class="fa fa-pencil fa-3x" aria-hidden="true"></i>
                    </span>
                    <span class="status icon hidden">
                        <i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
                    </span>
                    <span class="priority icon hidden">
                        <i class="fa fa-star fa-3x" aria-hidden="true"></i>
                    </span>
            </div>

        </form>
    </div>
</div>

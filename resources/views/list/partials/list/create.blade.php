<div class="hidden modal main-menu new">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.store') }}">
            {{ csrf_field() }}
            
            <span class="modal-heading">
                Create a new list
            </span>

            <div class="first input">
                <label for="list-create-name">Name:</label>
                <input id="list-create-name" type="text" name="name">
            </div>

            <div class="form-buttons">
                <span class="cancel btn white">Cancel</span>
                <span class="submit btn pink">Submit</span>
            </div>

            <div class="fake action-menu">
                <ul>
                    <li class="action-button create">
                        <i class="fa fa-plus-square fa-3x highlighted" aria-hidden="true"></i>
                    </li>
                    <li class="action-button delete">
                        <i class="fa fa-minus-square fa-3x" aria-hidden="true"></i>
                    </li>
                    <li class="action-button edit">
                        <i class="fa fa-pencil-square-o fa-3x" aria-hidden="true"></i>
                    </li>
                    <li class="action-button status">
                        <i class="fa fa-check-square-o fa-3x" aria-hidden="true"></i>
                    </li>
                    <li class="action-button priority">
                        <i class="fa fa-star fa-3x" aria-hidden="true"></i>
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>

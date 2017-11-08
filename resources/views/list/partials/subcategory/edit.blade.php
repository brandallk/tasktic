<div class="hidden modal edit subcategory">
    <div class="shadow-back">
        <form method="post" action="{{ route('subcategories.update', ['subcategory' => $subcategory->id]) }}">
            {{ csrf_field() }}

            {{ method_field('PATCH') }}

            <span class="modal-heading">
                Change the subcategory name
            </span>

            <div class="first input">
                <label for="subcategory-edit-name">Name:</label>
                <input id="subcategory-edit-name" type="text" name="name">
            </div>

            <div class="form-buttons">
                <span class="cancel btn white">Cancel</span>
                <span class="submit btn pink">Submit</span>
            </div>

            <div class="fake action-menu">
                <ul>
                    <li class="action-button create">
                        <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                    </li>
                    <li class="action-button delete">
                        <i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
                    </li>
                    <li class="action-button edit">
                        <i class="fa fa-pencil-square-o fa-3x highlighted" aria-hidden="true"></i>
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

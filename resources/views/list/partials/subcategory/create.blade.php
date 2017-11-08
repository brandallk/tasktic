<div class="hidden modal create subcategory">
    <div class="shadow-back">
        <form method="post" action="{{ route('subcategories.store') }}">
            {{ csrf_field() }}

            <span class="modal-heading">
                Add a new subcategory
            </span>

            <div class="first input">
                <label for="subcategory-create-name">Name:</label>
                <input id="subcategory-create-name" type="text" name="name">
            </div>

            <input type="hidden" name="categoryID" value="{{ $category->id }}">

            <div class="form-buttons">
                <span class="cancel btn white">Cancel</span>
                <span class="submit btn pink">Submit</span>
            </div>

            <div class="fake action-menu">
                <ul>
                    <li class="action-button create">
                        <i class="fa fa-plus-circle fa-3x highlighted" aria-hidden="true"></i>
                    </li>
                    <li class="action-button delete">
                        <i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
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

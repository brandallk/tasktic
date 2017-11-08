<div class="hidden modal delete subcategory">
    <div class="shadow-back">
        <form method="post" action="{{ route('subcategories.destroy', ['subcategory' => $subcategory->id]) }}">
            {{ csrf_field() }}

            {{ method_field('DELETE') }}

            <span class="modal-heading">
                Delete the subcategory
            </span>

            <div class="message">
                <p>
                    ...and everything in it! &nbsp;
                    <img src="{{ asset('img/mushroomcloud.png') }}" alt="mushroom cloud">
                </p>
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
                        <i class="fa fa-times-circle fa-3x highlighted" aria-hidden="true"></i>
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

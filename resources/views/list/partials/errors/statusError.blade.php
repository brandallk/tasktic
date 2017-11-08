<div class="hidden modal error-statusError">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.create.element', ['list' => $list->id]) }}">
            {{ csrf_field() }}

            <span class="modal-heading">
                Oops! No selection.
            </span>

            <div class="message">
                <p>
                    This button marks a task as complete or incomplete.
                </p>
                <p>
                    Click a task on your List first.
                    <i class="fa fa-smile-o" aria-hidden="true"></i>
                </p>
            </div>

            <div class="form-buttons">
                <span class="aknowledge btn white">OK</span>
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
                        <i class="fa fa-pencil-square-o fa-3x" aria-hidden="true"></i>
                    </li>
                    <li class="action-button status">
                        <i class="fa fa-check-square-o fa-3x highlighted" aria-hidden="true"></i>
                    </li>
                    <li class="action-button priority">
                        <i class="fa fa-star fa-3x" aria-hidden="true"></i>
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>

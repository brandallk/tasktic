<div class="hidden modal create item">
    <div class="shadow-back">
        <form method="post" action="{{ route('items.store') }}">
            {{ csrf_field() }}

            <span class="modal-heading">
                New:
            </span>
            <select class="modal-selectBox" name="type">
                <option selected="selected" value="detail">detail</option>
                <option value="link">link</option>
            </select>

            <div class="first input">
                <label for="item-create-content">Content:</label>
                <input id="item-create-content" type="text" name="content">
            </div>

            <input type="hidden" name="taskID" value="{{ $task->id }}">

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

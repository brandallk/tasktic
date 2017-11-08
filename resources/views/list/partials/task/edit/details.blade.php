<div class="hidden modal edit task">
    <div class="shadow-back">
        <form method="post" action="{{ route('tasks.update.details', ['task' => $task->id]) }}">
            {{ csrf_field() }}

            {{ method_field('PATCH') }}

            <span class="modal-heading">
                Change the task name and/or deadline
            </span>

            <div class="first input">
                <label for="task-edit-name">Name:</label>
                <input id="task-edit-name" type="text" name="name">
            </div>

            <div class="second input">
                <label for="task-create-deadline">Deadline:</label>
                <input id="task-create-deadline" type="text" name="deadline" placeholder="(optional)">
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

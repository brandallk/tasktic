<div class="hidden modal create item">
    <div class="shadow-back">
        <form method="post" action="{{ route('items.store') }}">
            {{ csrf_field() }}

            <div class="heading-wrapper">
                <span class="modal-heading">
                    Add to this task
                </span>
            </div>

            <div class="inputs-wrapper">
                <div class="select input">
                    <label for="item-create-type">New:</label>
                    <select id="item-create-type" class="modal-selectBox" name="type">
                        <option selected="selected" value="detail">detail</option>
                        <option value="link">link</option>
                    </select>
                </div>

                <div class="first input">
                    <label for="item-create-content">Content:</label>
                    <input id="item-create-content" type="text" name="content">
                </div>

                <input type="hidden" name="taskID" value="{{ $task->id }}">

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

<div class="hidden modal create-listElement">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.create.element', ['list' => $list->id]) }}">
            {{ csrf_field() }}

            <div class="heading-wrapper">
                <span class="modal-heading">
                    Add to List
                </span>
            </div>

            <div class="inputs-wrapper">
                <div class="select input">
                    <label for="listElement-create-type">New:</label>
                    <select id="listElement-create-type" class="modal-selectBox" name="elementType" size="1">
                        <option selected="selected" value="task">Task</option>
                        <option value="subcategory">Task Subcategory</option>
                        <option value="category">Task Category</option>
                    </select>
                </div>

                <div class="first input">
                    <label for="listElement-create-name">Name:</label>
                    <input id="listElement-create-name" type="text" name="name">
                </div>

                <div class="second input">
                    <label for="listElement-create-deadline">Deadline:</label>
                    <input id="listElement-create-deadline" type="text" name="deadline" placeholder="(optional)">
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

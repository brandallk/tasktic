<div class="hidden modal create-listElement">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.create.element', ['list' => $list->id]) }}">
            {{ csrf_field() }}

            <!-- <span class="modal-heading">
                Add to the list
            </span>

            <div class="first input">
                <label for="listElement-create-type">What to add:</label>
                <select id="listElement-create-type" name="elementType" size="1">
                    <option selected="selected" value="task">Task</option>
                    <option value="subcategory">Task Subcategory</option>
                    <option value="category">Task Category</option>
                </select>
            </div>

            <div class="second input">
                <label for="listElement-create-name">Name:</label>
                <input id="listElement-create-name" type="text" name="name">
            </div>

            <div class="third input">
                <label for="listElement-create-deadline">Deadline:</label>
                <input id="listElement-create-deadline" type="text" name="deadline">
            </div> -->

            <span class="modal-heading">
                New:
            </span>
            <select id="listElement-create-type" name="elementType" size="1">
                <option selected="selected" value="task">Task</option>
                <option value="subcategory">Task Subcategory</option>
                <option value="category">Task Category</option>
            </select>

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

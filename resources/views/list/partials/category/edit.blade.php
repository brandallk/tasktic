<div class="hidden short modal edit category">
    <div class="shadow-back">
        <form method="post" action="{{ route('categories.update', ['category' => $category->id]) }}">
            {{ csrf_field() }}

            {{ method_field('PATCH') }}

            <div class="heading-wrapper">
                <span class="modal-heading">
                    Change the category name
                </span>
            </div>

            <div class="inputs-wrapper">
                <div class="first input">
                    <label for="category-edit-name">Name:</label>
                    <input id="category-edit-name" type="text" name="name" value="{{ optional($category)->name }}">
                </div>

                <div class="form-buttons">
                    <span class="cancel btn white">Cancel</span>
                    <span class="submit btn pink">Submit</span>
                </div>
            </div>

            <div class="action icons">
                    <span class="create icon hidden">
                        <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                    </span>
                    <span class="delete icon hidden">
                        <i class="fa fa-times-circle fa-3x" aria-hidden="true"></i>
                    </span>
                    <span class="edit icon active">
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

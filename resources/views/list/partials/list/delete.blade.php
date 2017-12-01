<div class="hidden short modal delete taskList">
    <div class="shadow-back">
        <form method="post" action="{{ route('lists.destroy', ['list' => $list->id]) }}">
            {{ csrf_field() }}

            {{ method_field('DELETE') }}

            <div class="heading-wrapper">
                <span class="modal-heading">
                    Delete the {{ $list->name }} list?
                </span>
            </div>

            <div class="inputs-wrapper">
                <div class="message">
                    <p>
                        ...and everything in it!
                    </p>
                </div>

                <div class="form-buttons">
                    <span class="cancel btn white">No</span>
                    <span class="submit btn pink">Yes</span>
                </div>
            </div>

        </form>
    </div>
</div>

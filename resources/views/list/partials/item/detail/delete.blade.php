<div class="hidden short modal delete detail">
    <div class="shadow-back">
        <form method="post" action="{{ route('items.destroy.detail', ['item' => $detail->id]) }}">
            {{ csrf_field() }}

            {{ method_field('DELETE') }}

            <div class="heading-wrapper">
                <span class="modal-heading">
                    Delete the detail?
                </span>
            </div>

            <div class="inputs-wrapper">
                <div class="form-buttons">
                    <span class="cancel btn white">No</span>
                    <span class="submit btn pink">Yes</span>
                </div>
            </div>

            <div class="action icons">
                    <span class="create icon hidden">
                        <i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i>
                    </span>
                    <span class="delete icon active">
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

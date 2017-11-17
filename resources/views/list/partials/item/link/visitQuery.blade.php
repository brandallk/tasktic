<div class="hidden short modal visitQuery link">
    <div class="shadow-back">
        <form>
            <div class="heading-wrapper">
                <span class="modal-heading">
                    Visit {{ $link->link }} ?
                </span>
            </div>

            <div class="inputs-wrapper">
                <div class="form-buttons">
                    <span class="cancel btn white">No</span>
                    <span class="cancel btn pink">
                        <a href="{{ $link->link }}" target="_blank">
                            Yes
                        </a>
                    </span>
                </div>
            </div>

            <div class="action icons">
                    <span class="query icon active">
                        <i class="fa fa-question-circle fa-3x" aria-hidden="true"></i>
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

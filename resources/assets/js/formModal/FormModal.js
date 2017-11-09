export default class FormModal {
    
    constructor(formModal) {
        this.formModal = formModal;
        this.form = formModal.querySelector('form');
        this.cancelButton = formModal.querySelector('.cancel.btn');
        this.submitButton = formModal.querySelector('.submit.btn');
    }

    activate() {
        if (this.cancelButton) {
            this.cancelButton.addEventListener('click', this.hideModal.bind(this));
        }

        if (this.submitButton) {
            this.submitButton.addEventListener('click', this.submitForm.bind(this));
        }
    }

    hideModal() {
        if (!this.formModal.classList.contains('hidden')) {
            this.formModal.classList.add('hidden');
        }
    }

    submitForm() {
        this.form.submit();
    }

}
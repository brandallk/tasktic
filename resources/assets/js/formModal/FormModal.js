export default class FormModal {
    
    constructor(formModal) {
        this.domElement    = formModal;
        this.form          = this.domElement.querySelector('form');
        this.submitButton  = this.domElement.querySelector('.submit.btn');

        // (A form can have multiple cancel buttons with differing behavior.)
        this.cancelButtons = this.domElement.querySelectorAll('.cancel.btn');

        this.createEltSelectBox =
            this.domElement.querySelector('.modal.create-listElement select');
    }

    activate() {
        if (this.cancelButtons) {
            this.cancelButtons.forEach( (button) => {
                button.addEventListener('click', this.hide.bind(this));
            });
        }

        if (this.submitButton) {
            this.submitButton.addEventListener('click', this.submitForm.bind(this));
        }

        if (this.createEltSelectBox) {
            this.createEltSelectBox.addEventListener(
                'change', this.conditionallyShowDeadlineInput.bind(this));
        }
    }

    show() {
        if (this.domElement.classList.contains('hidden')) {
            this.domElement.classList.remove('hidden');
        }
    }

    hide() {
        if (!this.domElement.classList.contains('hidden')) {
            this.domElement.classList.add('hidden');

            // Prevent bubbling the event up to a parent selectable element
            event.stopPropagation();
        }
    }

    submitForm() {
        this.form.submit();
    }

    conditionallyShowDeadlineInput() {
        if (this.createEltSelectBox.value == 'task') {
            this.form.querySelector('.second.input').classList.remove('hidden');
        } else {
            this.form.querySelector('.second.input').classList.add('hidden');
        }
    }

}
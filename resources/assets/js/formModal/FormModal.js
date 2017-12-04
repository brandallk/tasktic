export default class FormModal {
    
    constructor(formModal) {
        this.domElement    = formModal;
        this.form          = this.domElement.querySelector('form');
        this.submitButton  = this.domElement.querySelector('.submit.btn');

        // (A form can have multiple cancel buttons with differing behavior.)
        this.cancelButtons = this.domElement.querySelectorAll('.cancel.btn');

        this.createEltSelectBox =
            this.domElement.querySelector('.modal.create-listElement select');

        this.createTaskItemSelectBox =
            this.domElement.querySelector('.modal.create.item select');
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

        if (this.createTaskItemSelectBox) {
            this.createTaskItemSelectBox.addEventListener(
                'change', this.conditionallyChangeInputLabel.bind(this));
        }
    }

    show() {
        if (this.domElement.classList.contains('hidden')) {

            this.domElement.classList.remove('hidden');
        }

        // Prevent unintended draggable behavior inherited from the containing <div> element
        if (this.domElement.parentElement.getAttribute('draggable') == "true") {

            this.domElement.parentElement.setAttribute('draggable', "false");
        }
    }

    hide() {
        if (!this.domElement.classList.contains('hidden')) {

            this.domElement.classList.add('hidden');

            // Prevent bubbling the event up to a parent selectable element
            event.stopPropagation();
        }

        // Re-establish draggable behavior for a containing Task <div> element
        if (this.domElement.parentElement.getAttribute('draggable') == "false" &&
            this.domElement.parentElement.classList.contains('task')) {

            this.domElement.parentElement.setAttribute('draggable', "true");
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

    conditionallyChangeInputLabel() {
        if (this.createTaskItemSelectBox.value == 'link') {
            this.form.querySelector('.first.input label').innerHTML = 'URL:';
        } else {
            this.form.querySelector('.first.input label').innerHTML = 'Content:';
        }
    }

}
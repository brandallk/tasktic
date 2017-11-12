import ActionMenuButton from './ActionMenuButton';

export default class PriorityButton extends ActionMenuButton {

    constructor(actionMenu, domElement) {
        super(actionMenu, domElement);
        this.action = 'togglePriorityStatus';
    }

    activate() {
        super.activate();
        this.domElement.addEventListener('click', this.submitForm);
    }

    deactivate() {
        super.deactivate();
        this.domElement.removeEventListener('click', this.submitForm);
    }

    submitForm() {
        // Note: 'this' == CreateButton.domElement and 'this.parentClass' == CreateButton.
        const taskList = this.parentClass.actionMenu.taskList;
        const selectedListElement = taskList.selected;
        const formModal = selectedListElement.formModals.togglePriorityStatus;

        const form = formModal.querySelector('form');

        form.submit();

        // Prevent bubbling the event up to a parent element
        event.stopPropagation();
    }

}
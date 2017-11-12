import ActionMenuButton from './ActionMenuButton';
import FormModal from '../../../formModal/FormModal';

export default class CreateButton extends ActionMenuButton {

    constructor(actionMenu, domElement) {
        super(actionMenu, domElement);
        this.action = 'createChild';
        this.defaultFormModal = document.querySelector('.modal.create-listElement');
    }

    // Activated state when no List Element is selected
    activateDefaultBehavior() {
        this.domElement.parentClass = this;
        this.domElement.addEventListener('click', this.showDefaultModal);
    }

    // Activated state when a List Element has been selected
    activate() {
        super.activate();
        this.domElement.removeEventListener('click', this.showDefaultModal);
        this.domElement.addEventListener('click', this.showModal);
    }

    deactivate() {
        super.deactivate();
        this.domElement.removeEventListener('click', this.showModal);
    }

    showDefaultModal() {
        // Note: 'this' == CreateButton.domElement and 'this.parentClass' == CreateButton.
        const formModal = new FormModal(this.parentClass.defaultFormModal);

        formModal.show();
        formModal.activate();

        // Prevent bubbling the event up to a parent element
        event.stopPropagation();
    }

    showModal() {
        // Note: 'this' == CreateButton.domElement and 'this.parentClass' == CreateButton.
        const taskList = this.parentClass.actionMenu.taskList;
        const selectedListElement = taskList.selected;

        const formModal = new FormModal(selectedListElement.formModals.createChild);

        formModal.show();
        formModal.activate();

        // Prevent bubbling the event up to a parent element
        event.stopPropagation();
    }

}
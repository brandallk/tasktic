import ActionMenuButton from './ActionMenuButton';
import FormModal from '../../../formModal/FormModal';

export default class EditButton extends ActionMenuButton {

    constructor(actionMenu, domElement) {
        super(actionMenu, domElement);
        this.action = 'editSelf';
    }

    activate() {
        super.activate();
        this.domElement.addEventListener('click', this.showModal);
    }

    deactivate() {
        super.deactivate();
        this.domElement.removeEventListener('click', this.showModal);
    }

    showModal() {
        // Note: 'this' == CreateButton.domElement and 'this.parentClass' == CreateButton.
        const taskList = this.parentClass.actionMenu.taskList;
        const selectedListElement = taskList.selected;

        const formModal = new FormModal(selectedListElement.formModals.editSelf);

        formModal.show();
        formModal.activate();

        // Prevent bubbling the event up to a parent element
        event.stopPropagation();
    }

}
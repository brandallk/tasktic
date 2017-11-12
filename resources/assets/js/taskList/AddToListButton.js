import FormModal from '../formModal/FormModal';

export default class AddToListButton {

    constructor(taskList) {
        this.taskList   = taskList;
        this.domElement = this.taskList.domElement.querySelector('.add-listElement.btn');
        this.formModal  = document.querySelector('.modal.create-listElement');
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            const formModal = new FormModal(this.formModal);

            formModal.show();
            formModal.activate();

            // Prevent bubbling the event up to a parent element
            event.stopPropagation();
        });
    }

}
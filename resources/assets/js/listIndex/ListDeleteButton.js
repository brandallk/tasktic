import FormModal from '../formModal/FormModal';

export default class ListDeleteButton {
    
    constructor(buttonElement) {
        this.domElement = buttonElement;
        this.formModal  = buttonElement.parentElement.querySelector('.modal.delete.taskList');
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            const formModal = new FormModal(this.formModal);
            formModal.show();
            formModal.activate();
        });
    }

}
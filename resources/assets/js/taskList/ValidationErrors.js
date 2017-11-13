import FormModal from '../formModal/FormModal';

export default class ValidationErrors {

    constructor(taskList) {
        this.formModal = taskList.domElement.querySelector('.alert.modal');
    }

    activate() {
        const formModal = new FormModal(this.formModal);
        formModal.activate();
    }

}
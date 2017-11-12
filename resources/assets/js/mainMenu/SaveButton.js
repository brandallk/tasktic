import FormModal from '../formModal/FormModal';

export default class saveButton {
    
    constructor(mainMenu) {
        this.menu       = mainMenu;
        this.domElement = this.menu.domElement.querySelector('li.save');
        this.formModal  = document.querySelector('div.modal.main-menu.save');
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            const formModal = new FormModal(this.formModal);
            formModal.show();
            formModal.activate();
        });
    }

}
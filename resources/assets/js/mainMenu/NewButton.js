import FormModal from '../formModal/FormModal';

export default class NewButton {
    
    constructor(mainMenu) {
        this.menu       = mainMenu;
        this.domElement = this.menu.domElement.querySelector('li.new');
        this.formModal  = document.querySelector('div.modal.main-menu.new');
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            const formModal = new FormModal(this.formModal);
            formModal.show();
            formModal.activate();
        });
    }

}
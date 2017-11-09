import FormModal from '../formModal/FormModal';

export default class saveButton {
    
    constructor(mainMenu) {
        this.menu = mainMenu;
        this.saveButton = this.menu.querySelector('li.save');
        this.formModal = document.querySelector('div.modal.main-menu.save');
    }

    activate() {
        this.saveButton.addEventListener('click', () => {
            if (this.formModal.classList.contains('hidden')) {
                this.formModal.classList.remove('hidden');
            }

            const saveForm = new FormModal(this.formModal);
            saveForm.activate();
        });
    }

}
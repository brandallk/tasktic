import FormModal from '../formModal/FormModal';

export default class NewButton {
    
    constructor(mainMenu) {
        this.menu = mainMenu;
        this.newButton = this.menu.querySelector('li.new');
        this.formModal = document.querySelector('div.modal.main-menu.new');
    }

    activate() {
        this.newButton.addEventListener('click', () => {
            if (this.formModal.classList.contains('hidden')) {
                this.formModal.classList.remove('hidden');
            }

            const newForm = new FormModal(this.formModal);
            newForm.activate();
        });
    }

}
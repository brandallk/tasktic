import TaskItem from './TaskItem';
import FormModal from '../../../../formModal/FormModal';

export default class Link extends TaskItem {
    
    constructor(task, link) {
        super(task, link);
        this.actions = ['deleteSelf', 'editSelf'];
        this.formModals = {
            deleteSelf:     this.domElement.querySelector('.modal.link.delete'),
            editSelf:       this.domElement.querySelector('.modal.link.edit'),
            URLvisitQuery:  this.domElement.querySelector('.modal.visitQuery')
        };
    }

    activate() {
        super.activate();
        this.domElement.addEventListener('click', () => { console.log('clicking on ', this.domElement);
            // Ask if user wants to visit the link URL
            const formModal = new FormModal(this.formModals.URLvisitQuery);
            formModal.show();
            formModal.activate();

            // Prevent bubbling the event up to a parent selectable element
            event.stopPropagation();
        });
    }

}
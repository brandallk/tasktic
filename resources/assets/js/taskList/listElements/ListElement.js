import TaskList from '../TaskList';

export default class ListElement {
    
    constructor(taskList, domElement) {
        this.taskList   = taskList;
        this.domElement = domElement;
        this.actions    = [];
        this.formModals = {};
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            this.markNewSelection();
            this.taskList.actionMenu.refresh(this.actions);

            // Prevent bubbling the event up to a parent selectable element
            event.stopPropagation();
        });
    }

    markNewSelection() {
        this.taskList.clearSelected();
        this.taskList.setSelected(this);
        this.domElement.classList.add('selected');
    }

}
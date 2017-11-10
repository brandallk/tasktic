import TaskList from '../TaskList';

export default class ListElement {
    
    constructor(taskList, DOMelement) {
        this.taskList = taskList;
        this.DOMelement = DOMelement;
    }

    activate() {
        this.DOMelement.addEventListener('click', () => {
            this.markNewSelection();

            // Prevent bubbling the event up to a parent selectable element
            event.stopPropagation();
        });
    }

    markNewSelection() {
        this.taskList.clearSelected();
        this.taskList.setSelected(this.DOMelement);
        this.DOMelement.classList.add('selected');
    }

}
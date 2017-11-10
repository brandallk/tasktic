export default class TaskItem {
    
    constructor(task, DOMelement) {
        this.task = task;
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
        this.task.taskList.clearSelected();
        this.task.taskList.setSelected(this.DOMelement);
        this.task.clearSelected();
        this.task.setSelected(this.DOMelement);
        this.task.DOMelement.classList.add('selected');
        this.DOMelement.classList.add('selected');
    }

}
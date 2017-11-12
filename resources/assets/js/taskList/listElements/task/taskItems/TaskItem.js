export default class TaskItem {
    
    constructor(task, domElement) {
        this.task       = task;
        this.domElement = domElement;
        this.actions    = null;
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            this.markNewSelection();
            this.task.taskList.actionMenu.refresh(this.actions);

            // Prevent bubbling the event up to a parent selectable element
            event.stopPropagation();
        });
    }

    markNewSelection() {
        this.task.taskList.clearSelected();
        this.task.taskList.setSelected(this);
        this.task.clearSelected();
        this.task.setSelected(this);
        this.task.domElement.classList.add('selected');
        this.domElement.classList.add('selected');
    }

}
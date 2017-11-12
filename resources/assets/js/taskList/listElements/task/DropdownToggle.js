export default class DropdownToggle {

    constructor(task) {
        this.task       = task;
        this.domElement = this.task.domElement.querySelector('.task-toggle');
        this.icon       = this.domElement.querySelector('i');
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            this.icon.classList.toggle('fa-caret-down');
            this.icon.classList.toggle('fa-caret-up');

            this.domElement.classList.toggle('down');
            this.domElement.classList.toggle('up');

            if (this.task.getTaskItems()) {
                this.task.getTaskItems().forEach( (item) => {
                    item.domElement.classList.toggle('hidden');
                });
            }
        });
    }

}
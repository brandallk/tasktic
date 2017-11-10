export default class DropdownToggle {

    constructor(task) {
        this.task = task;
        this.DOMelement = this.task.DOMelement.querySelector('.task-toggle');
        this.icon = this.DOMelement.querySelector('i');
    }

    activate() {
        this.DOMelement.addEventListener('click', () => {
            this.icon.classList.toggle('fa-caret-down');
            this.icon.classList.toggle('fa-caret-up');

            this.DOMelement.classList.toggle('down');
            this.DOMelement.classList.toggle('up');

            if (this.task.getTaskItems()) {
                this.task.getTaskItems().forEach( (item) => {
                    item.DOMelement.classList.toggle('hidden');
                });
            }
        });
    }

}
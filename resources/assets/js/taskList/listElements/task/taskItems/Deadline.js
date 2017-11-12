import TaskItem from './TaskItem';

export default class Deadline extends TaskItem {
    
    constructor(task, deadline) {
        super(task, deadline);
        this.actions = ['deleteSelf'];
        this.formModals = {
            deleteSelf: this.domElement.querySelector('.modal.deadline.delete')
        };
    }

}
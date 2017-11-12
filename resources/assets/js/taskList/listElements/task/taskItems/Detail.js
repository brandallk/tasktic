import TaskItem from './TaskItem';

export default class Detail extends TaskItem {
    
    constructor(task, detail) {
        super(task, detail);
        this.actions = ['deleteSelf', 'editSelf'];
        this.formModals = {
            deleteSelf:  this.domElement.querySelector('.modal.detail.delete'),
            editSelf:    this.domElement.querySelector('.modal.detail.edit')
        };
    }

}
import ListElement from '../ListElement';

export default class Subcategory extends ListElement {
    
    constructor(taskList, subcategory) {
        super(taskList, subcategory);
        this.actions = ['createChild', 'deleteSelf', 'editSelf'];
        this.formModals = {
            createChild:  this.domElement.querySelector('.modal.task.create'),
            deleteSelf:   this.domElement.querySelector('.modal.subcategory.delete'),
            editSelf:     this.domElement.querySelector('.modal.subcategory.edit')
        };
    }

}
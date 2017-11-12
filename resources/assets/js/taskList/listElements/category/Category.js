import ListElement from '../ListElement';

export default class Category extends ListElement {
    
    constructor(taskList, category) {
        super(taskList, category);
        this.actions = ['createChild', 'deleteSelf', 'editSelf'];
        this.formModals = {
            createChild:  this.domElement.querySelector('.modal.subcategory.create'),
            deleteSelf:   this.domElement.querySelector('.modal.category.delete'),
            editSelf:     this.domElement.querySelector('.modal.category.edit')
        };
    }

}
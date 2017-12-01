import ListDeleteButton from './ListDeleteButton';

export default class ListIndex {
    
    constructor() {
        this.domElement = document.querySelector('ul.taskListIndex');
        this.deleteButtons  = this.getDeleteButtons();
    }

    getDeleteButtons() {
        const buttonElements = Array.from(document.querySelectorAll('span.deleteListButton'));
        const buttons = [];

        buttonElements.forEach( (buttonElement) => {
            buttons.push(new ListDeleteButton(buttonElement));
        });

        return buttons;
    }

    activate() {
        this.deleteButtons.forEach( (button) => {
            button.activate();
        });
    }

}
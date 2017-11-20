import CreateButton from './buttons/CreateButton';
import DeleteButton from './buttons/DeleteButton';
import EditButton from './buttons/EditButton';
import StatusButton from './buttons/StatusButton';
import PriorityButton from './buttons/PriorityButton';

export default class ActionMenu {

    constructor(taskList) {
        this.taskList       = taskList;
        this.domElement     = document.querySelector('div.action-menu');
        this.createButton   = new CreateButton(this, this.domElement.querySelector('li.create'));
        this.deleteButton   = new DeleteButton(this, this.domElement.querySelector('li.delete'));
        this.editButton     = new EditButton(this, this.domElement.querySelector('li.edit'));
        this.statusButton   = new StatusButton(this, this.domElement.querySelector('li.status'));
        this.priorityButton = new PriorityButton(this, this.domElement.querySelector('li.priority'));
        this.buttons = [
            this.createButton,
            this.deleteButton,
            this.editButton,
            this.statusButton,
            this.priorityButton
        ];
    }

    refresh(actions) {
        this.buttons.forEach( (button) => {
            if (actions.includes(button.action)) {
                button.activate();
            } else {
                button.deactivate();
            }
        });
    }

    activateDefaultBehavior() {
        // Deactivate any activated menu buttons...
        this.buttons.forEach( (button) => {
            button.deactivate();
        });

        // But show them all...
        this.buttons.forEach( (button) => {
            if (button.domElement.classList.contains('hidden')) {
                button.domElement.classList.remove('hidden');
            }
        });

        // And activate the createButton's default behavior
        this.createButton.activateDefaultBehavior();
    }

}
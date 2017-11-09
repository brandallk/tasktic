import SaveButton from './SaveButton';
import NewButton from './NewButton';
import LoadButton from './LoadButton';
import LogoutButton from './LogoutButton';
import ToggleButton from './ToggleButton';

export default class MainMenu {
    
    constructor() {
        this.DOMelement = document.querySelector('div.main-menu');
        this.saveButton = new SaveButton(this.DOMelement);
        this.newButton = new NewButton(this.DOMelement);
        this.loadButton = new LoadButton(this.DOMelement);
        this.logoutButton = new LogoutButton(this.DOMelement);
        this.toggleButton = new ToggleButton(this.DOMelement);
    }

    activate() {
        this.saveButton.activate();
        this.newButton.activate();
        this.loadButton.activate();
        this.logoutButton.activate();
        this.toggleButton.activate();
    }

}
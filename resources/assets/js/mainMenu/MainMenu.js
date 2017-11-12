import SaveButton from './SaveButton';
import NewButton from './NewButton';
import LoadButton from './LoadButton';
import LogoutButton from './LogoutButton';
import ToggleButton from './ToggleButton';

export default class MainMenu {
    
    constructor() {
        this.domElement = document.querySelector('div.main-menu');
        this.buttons = {
            save:    new SaveButton(this),
            new:     new NewButton(this),
            load:    new LoadButton(this),
            logout:  new LogoutButton(this),
            toggle:  new ToggleButton(this)
        };
    }

    activate() {
        for (const button in this.buttons) {
            this.buttons[button].activate();
        }
    }

}
export default class ActionMenuButton {

    constructor(actionMenu, domElement) {
        this.actionMenu = actionMenu;
        this.domElement = domElement;
        this.action     = null;
    }

    activate() {
        if (this.domElement.classList.contains('hidden')) {
            this.domElement.classList.remove('hidden');
        }
        this.domElement.parentClass = this;
    }

    deactivate() {
        if (!this.domElement.classList.contains('hidden')) {
            this.domElement.classList.add('hidden');
        }
        this.domElement.parentClass = undefined;
    }

}
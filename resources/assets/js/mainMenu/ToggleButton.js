export default class ToggleButton {
    
    constructor(mainMenu) {
        this.menu = mainMenu;
        this.domElement = document.querySelector('.menu-toggle');
        this.toggleIcon = this.domElement.querySelector('.fa');
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            this.menu.domElement.classList.toggle('up');
            this.domElement.classList.toggle('down');
            this.domElement.classList.toggle('up');
            this.toggleIcon.classList.toggle('fa-caret-down');
            this.toggleIcon.classList.toggle('fa-caret-up');
        });
    }

}
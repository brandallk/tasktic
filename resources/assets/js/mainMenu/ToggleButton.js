export default class ToggleButton {
    
    constructor(mainMenu) {
        this.menu = mainMenu;
        this.toggleButton = document.querySelector('.menu-toggle');
        this.toggleIcon = this.toggleButton.querySelector('.fa');
    }

    activate() {
        this.toggleButton.addEventListener('click', () => {
            this.menu.classList.toggle('up');
            this.toggleButton.classList.toggle('down');
            this.toggleButton.classList.toggle('up');
            this.toggleIcon.classList.toggle('fa-caret-down');
            this.toggleIcon.classList.toggle('fa-caret-up');
        });
    }

}
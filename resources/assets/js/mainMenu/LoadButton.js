export default class LoadButton {
    
    constructor(mainMenu) {
        this.menu       = mainMenu;
        this.domElement = this.menu.domElement.querySelector('li.load');
        this.dropdown   = this.domElement.querySelector('ul.dropdown');
        this.hideDropdownContext = {
            mainMenuSaveButton:    this.menu.domElement.querySelector('li.save'),
            mainMenuNewButton:     this.menu.domElement.querySelector('li.new'),
            mainMenuLogoutButton:  this.menu.domElement.querySelector('li.logout'),
            mainMenuToggleButton:  document.querySelector('.menu-toggle')
        };
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            this.dropdown.classList.toggle('hidden');
        });

        for (const ctxElt in this.hideDropdownContext) {
            this.hideDropdownContext[ctxElt].addEventListener('mouseover', () => {
                this.dropdown.classList.add('hidden');
            });
        }
    }

}
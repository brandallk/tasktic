export default class LoadButton {
    
    constructor(mainMenu) {
        this.menu = mainMenu;
        this.loadButton = this.menu.querySelector('li.load');
        this.dropdown = this.loadButton.querySelector('ul.dropdown');
        this.hideDropdownContext = [
            this.menu.querySelector('li.save'),
            this.menu.querySelector('li.new'),
            this.menu.querySelector('li.logout'),
            document.querySelector('.menu-toggle')
        ];
    }

    activate() {
        this.loadButton.addEventListener('click', () => {
            this.dropdown.classList.toggle('hidden');
        });

        this.hideDropdownContext.forEach( (contextItem) => {
            contextItem.addEventListener('mouseover', () => {
                this.dropdown.classList.add('hidden');
            });
        });
    }

}
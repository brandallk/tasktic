export default class LogoutButton {
    
    constructor(mainMenu) {
        this.menu       = mainMenu;
        this.domElement = this.menu.domElement.querySelector('li.logout');
        this.form       = this.domElement.querySelector('form');
    }

    activate() {
        this.domElement.addEventListener('click', () => {
            this.form.submit();
        });
    }

}
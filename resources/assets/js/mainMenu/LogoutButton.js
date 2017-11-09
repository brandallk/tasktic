export default class LogoutButton {
    
    constructor(mainMenu) {
        this.menu = mainMenu;
        this.logoutButton = this.menu.querySelector('li.logout');
        this.form = this.logoutButton.querySelector('form');
    }

    activate() {
        this.logoutButton.addEventListener('click', () => {
            this.form.submit();
        });
    }

}
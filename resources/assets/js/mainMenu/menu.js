(function() {

    const mainMenu = document.querySelector('div.main-menu');
    const menuToggle = document.querySelector('span.menu-toggle');
    const menuToggleIcon = document.querySelector('span.menu-toggle i.fa');

    const saveButton = document.querySelector('li.action.save');
    const saveFormModal = document.querySelector('div.modal.main-menu.save');
    const saveForm = document.querySelector('div.modal.main-menu.save form');
    const saveFormCancel = document.querySelector('div.modal.main-menu.save .form-buttons .cancel.btn');
    const saveFormSubmit = document.querySelector('div.modal.main-menu.save .form-buttons .submit.btn');

    const newButton = document.querySelector('li.action.new');
    const createFormModal = document.querySelector('div.modal.main-menu.new');
    const createForm = document.querySelector('div.modal.main-menu.new form');
    const createFormCancel = document.querySelector('div.modal.main-menu.new .form-buttons .cancel.btn');
    const createFormSubmit = document.querySelector('div.modal.main-menu.new .form-buttons .submit.btn');

    const loadButton = document.querySelector('li.action.load');
    const loadDropdown = document.querySelector('ul.menu-list ul.dropdown');

    const logoutButton = document.querySelector('li.action.logout');
    const logoutForm = document.querySelector('li.action.logout form');

    const outsideLoadButton = [
        saveButton,
        newButton,
        logoutButton,
        menuToggleIcon
    ];

    menuToggle.addEventListener('click', function() {
        mainMenu.classList.toggle('up');
        menuToggle.classList.toggle('down');
        menuToggle.classList.toggle('up');
        menuToggleIcon.classList.toggle('fa-caret-down');
        menuToggleIcon.classList.toggle('fa-caret-up');
    });

    saveButton.addEventListener('click', function() {
        saveFormModal.classList.toggle('hidden');
    });

    saveFormCancel.addEventListener('click', function() {
        saveFormModal.classList.toggle('hidden');
    });

    saveFormSubmit.addEventListener('click', function() {
        saveForm.submit();
    });

    newButton.addEventListener('click', function() {
        createFormModal.classList.toggle('hidden');
    });

    createFormCancel.addEventListener('click', function() {
        createFormModal.classList.toggle('hidden');
    });

    createFormSubmit.addEventListener('click', function() {
        createForm.submit();
    });

    loadButton.addEventListener('click', function() {
        loadDropdown.classList.toggle('hidden');
    });

    logoutButton.addEventListener('click', function() {
        logoutForm.submit();
    });

    outsideLoadButton.forEach(function(item) {
        item.addEventListener('mouseover', function() {
            loadDropdown.classList.add('hidden');
        });
    });

})();

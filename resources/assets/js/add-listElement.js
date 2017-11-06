
(function() {

    const addButton = document.querySelector('.add-listElement.btn');
    const createFormModal = document.querySelector('div.modal.create-listElement');
    const createForm = document.querySelector('div.modal.create-listElement form');
    const createFormCancel = document.querySelector('div.modal.create-listElement .form-buttons .cancel.btn');
    const createFormSubmit = document.querySelector('div.modal.create-listElement .form-buttons .submit.btn');
    const typeSelector = document.querySelector('select#listElement-create-type');
    const deadlineInput = document.querySelector('div.modal.create-listElement div.second.input');

    addButton.addEventListener('click', function() {
        createFormModal.classList.toggle('hidden');
    });

    createFormCancel.addEventListener('click', function() {
        createFormModal.classList.toggle('hidden');
    });

    createFormSubmit.addEventListener('click', function() {
        createForm.submit();
    });

    typeSelector.addEventListener('change', function(event) {
        if (typeSelector.value == "task") {
            deadlineInput.classList.remove('hidden');
        } else {
            deadlineInput.classList.add('hidden');
        }
    });

})();
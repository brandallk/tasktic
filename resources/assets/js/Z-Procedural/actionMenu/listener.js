
(function() {

    const menuButtons = document.querySelectorAll('div.action-menu:not(.fake) li.action-button');

    menuButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const listElement = taskList.selectedElement;
            const listElementType = taskList.selectedElementType;

            const formModal = formModals.getModal(button, listElement, listElementType);
            const actionType = formModals.getActionType(button);

            if ((!listElement) || (actionType !== 'status' && actionType !== 'priority')) {
                formModal.classList.remove('hidden');
                activateFormControls(formModal);
            } else {
                formModal.querySelector('form').submit();
            }
        });
    });

    function activateFormControls(formModal) {

        const form = formModal.querySelector('form');
        const cancelButton = form.querySelector('.cancel.btn');
        const errorCancelButton = form.querySelector('.aknowledge.btn');
        const submitButton = form.querySelector('.submit.btn');

        function hideTheForm() {
            formModal.classList.toggle('hidden');

            // Prevent multiple event listeners being registered by repeat form loads
            if (cancelButton) {
                cancelButton.removeEventListener('click', hideTheForm);
            }
            if (errorCancelButton) {
                errorCancelButton.removeEventListener('click', hideTheForm);
            }
        };

        if (cancelButton) {
            cancelButton.addEventListener('click', hideTheForm);
        }

        if (errorCancelButton) {
            errorCancelButton.addEventListener('click', hideTheForm);
        }

        if (submitButton) {
            submitButton.addEventListener('click', function() {
                form.submit();
            });
        }

    };

})();

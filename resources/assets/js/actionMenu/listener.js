
(function() {

    const menuButtons = document.querySelectorAll('div.action-menu:not(.fake) li.action-button');
    const genericCreateFormModal = document.querySelector('div.modal.create-listElement');
    const deleteErrorModal = document.querySelector('div.modal.error-deleteError');
    const editErrorModal = document.querySelector('div.modal.error-editError');
    const statusErrorModal = document.querySelector('div.modal.error-statusError');
    const priorityErrorModal = document.querySelector('div.modal.error-priorityError');
    const list = document.querySelector('div.theList');

    menuButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const listElement = taskList.selectedElement;
            const listElementType = taskList.selectedElementType;
            const actionType = this.classList[0];
            let form = null;

            if (!listElement) {
                switch (actionType) {
                    case 'create':
                        form = genericCreateFormModal;
                        break;
                    case 'delete':
                        form = deleteErrorModal;
                        break;
                    case 'edit':
                        form = editErrorModal;
                        break;
                    case 'status':
                        form = statusErrorModal;
                        break;
                    case 'priority':
                        form = priorityErrorModal;
                        break;
                }
                form.classList.remove('hidden');

            } else if (actionType !== 'status' && actionType !== 'priority') {
                if (actionType == 'create') {
                    switch (listElementType) {
                        case 'category':
                            form = listElement.querySelector('div.modal.subcategory.create');
                            break;
                        case 'subcategory':
                            form = listElement.querySelector('div.modal.task.create');
                            break;
                        case 'task':
                            form = listElement.querySelector('div.modal.item.create');
                            break;
                    }
                } else {
                    form = listElement.querySelector(
                        'div.modal.' + actionType + '.' + listElementType
                    );
                }

                form.classList.remove('hidden');

            } else {
                form = listElement.querySelector(
                    'div.modal.' + actionType + '.' + listElementType
                );
                form.querySelector('form').submit();
            }
            
        });
    });

})();
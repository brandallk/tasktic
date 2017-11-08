
(function(exports) {

    const genericCreateFormModal = document.querySelector('div.modal.create-listElement');
    const deleteErrorModal = document.querySelector('div.modal.error-deleteError');
    const editErrorModal = document.querySelector('div.modal.error-editError');
    const statusErrorModal = document.querySelector('div.modal.error-statusError');
    const priorityErrorModal = document.querySelector('div.modal.error-priorityError');
    const list = document.querySelector('div.theList');

    function getActionType(button) {
        return button.classList[0];
    }

    function getModal(button, listElement, listElementType) {
        let form = null;
        const actionType = getActionType(button);

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

        } else {
            form = listElement.querySelector(
                'div.modal.' + actionType + '.' + listElementType
            );
        }

        return form;
    };

    exports.getModal = function(button, listElement, listElementType) {
        return getModal(button, listElement, listElementType);
    }

    exports.getActionType = function(button) {
        return getActionType(button)
    };

})(window.formModals = {});

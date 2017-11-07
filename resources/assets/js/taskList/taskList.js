
(function(exports) {

    function selectedElement(elementType, uniqueID, listElements, actionMenuButtons) {
        const selected = document.getElementById(uniqueID);
        markNewSelection(selected, listElements);
        const availableActions = getActionsByElementType(elementType);
        refreshTheActionMenu(availableActions, actionMenuButtons);
        exports.selectedElement = selected;
    };

    function markNewSelection(selected, listElements) {
        clearLastSelection(listElements);
        selected.classList.add('selected');
    }

    function clearLastSelection(listElements) {
        listElements.forEach(function(listElement) {
            if (listElement.classList.contains('selected')) {
                listElement.classList.remove('selected');
            }
        });
    }

    function getActionsByElementType(elementType) {
        let actions = [];

        // Get an array of (2ndary-menu) actions available to the element
        switch (elementType) {
            case 'category':
                actions = ['create', 'delete', 'edit'];
                break;
            case 'subcategory':
                actions = ['create', 'delete', 'edit'];
                break;
            case 'task':
                actions = ['create', 'delete', 'edit', 'status', 'priority'];
                break;
            case 'deadline':
                actions = ['delete'];
                break;
            case 'link':
                actions = ['delete', 'edit'];
                break;
            case 'detail':
                actions = ['delete', 'edit'];
                break;
        }

        return actions;
    }

    function refreshTheActionMenu(availableActions, actionMenuButtons) {
        actionMenuButtons.forEach(function(button) {
            if (!availableActions.includes(button.classList[0])) {
                if (!button.classList.contains('hidden')) {
                    button.classList.add('hidden');
                }
            } else {
                if (button.classList.contains('hidden')) {
                    button.classList.remove('hidden');
                }
            }
        });
    }

    exports.selectElement = function(elementType, uniqueID, listElements, actionMenuButtons) {
        return selectedElement(elementType, uniqueID, listElements, actionMenuButtons);
    };

    exports.selectedElement = null;

    exports.clearLastSelection = function(listElements) {
        return clearLastSelection(listElements);
    };
    
})(window.taskList = {});

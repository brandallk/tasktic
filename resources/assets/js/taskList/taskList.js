
(function(exports) {
    const actionMenuButtons = document.querySelectorAll('div.action-menu:not(.fake) li.action-button');
    const listElements = document.querySelectorAll('div.selectable');

    function selectTheGivenElement(elementType, uniqueID) {
        const selected = document.getElementById(uniqueID);
        markNewSelection(selected);
        const availableActions = getActionsByElementType(elementType);
        refreshTheActionMenu(availableActions);
        exports.selectedElement = selected;
        exports.selectedElementType = elementType;
    };

    function markNewSelection(selected) {
        clearLastSelection(listElements);
        selected.classList.add('selected');
    }

    function clearLastSelection() {
        listElements.forEach(function(listElement) {
            if (listElement.classList.contains('selected')) {
                listElement.classList.remove('selected');
            }
        });

        actionMenuButtons.forEach(function(button) {
            if (button.classList.contains('hidden')) {
                button.classList.remove('hidden');
            }
        });

        exports.selectedElement = null;
        exports.selectedElementType = null;
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

    function refreshTheActionMenu(availableActions) {
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

    exports.selectElement = function(elementType, uniqueID) {
        return selectTheGivenElement(elementType, uniqueID);
    };

    exports.selectedElement = null;

    exports.selectedElementType = null;

    exports.clearLastSelection = function() {
        return clearLastSelection();
    };
    
})(window.taskList = {});

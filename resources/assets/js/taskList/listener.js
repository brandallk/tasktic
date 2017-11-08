
(function() {

    const listElements = document.querySelectorAll('div.selectable');
    const actionMenuButtons = document.querySelectorAll('div.action-menu:not(.fake) li.action-button');
    const body = document.querySelector('body');

    // Capture a click event on a selectable task-list element
    listElements.forEach(function(listElement) {
        const elementType = listElement.classList[0];
        const uniqueID = listElement.id;

        listElement.addEventListener('click', function(event) {
            // Prevent the click event bubbling up to any parent 'selectable' elements
            event.stopPropagation();

            taskList.selectElement(elementType, uniqueID, listElements, actionMenuButtons);
        });
    });

    // Cancel any current selection if a click happens outside the selectable
    // area (the task list) or Action Menu (the 2ndary menu)
    body.addEventListener('click', function(event) {
        clickedClassList = Array.from(event.target.classList);

        const exemptClasses = [
            'selectable',
            'action-button',
            'action-icon'
        ];

        if (arrayHelpers.noneInArray(clickedClassList, exemptClasses)) {
            taskList.clearLastSelection(listElements);
            taskList.selectedElement = null;
            taskList.selectedElementType = null;
        }
    });
    
})();

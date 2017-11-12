import Category from './listElements/category/Category';
import Subcategory from './listElements/subcategory/Subcategory';
import Task from './listElements/task/Task';
import ActionMenu from './actionMenu/ActionMenu';
import AddToListButton from './AddToListButton';

export default class TaskList {
    
    constructor() {
        this.domElement            = document.querySelector('.theList');
        this.categories            = this.getCategories();
        this.subcategories         = this.getSubcategories();
        this.tasks                 = this.getTasks();
        this.listElements          = this.getListElements();
        this.selected              = null;
        this.actionMenu            = new ActionMenu(this);
        this.addToListButton       = new AddToListButton(this);
        this.clearSelectionContext = document.querySelector('body');
    }

    getCategories() {
        const categories = [];

        this.domElement.querySelectorAll('div.category.selectable').forEach( (category) => {
            categories.push(new Category(this, category));
        });

        return categories;
    }

    getSubcategories() {
        const subcategories = [];

        this.domElement.querySelectorAll('div.subcategory.selectable').forEach( (subcategory) => {
            subcategories.push(new Subcategory(this, subcategory));
        });

        return subcategories;
    }

    getTasks() {
        const tasks = [];

        this.domElement.querySelectorAll('div.task.selectable').forEach( (task) => {
            tasks.push(new Task(this, task));
        });

        return tasks;
    }

    getListElements() {
        return this.categories.concat(
            this.subcategories,
            this.tasks
        );
    }

    activate() {
        this.listElements.forEach( (listElement) => {
            listElement.activate();
        });

        this.activateClearSelectionContext();
        this.addToListButton.activate();
        this.actionMenu.activateDefaultBehavior();
    }

    // Clear the List's current 'selected' element if user clicks off the List
    activateClearSelectionContext() {
        this.clearSelectionContext.addEventListener('click', () => {
            this.clearSelected();
            this.actionMenu.activateDefaultBehavior();
        });
    }

    // Clear the List's current 'selected' element
    clearSelected() {
        this.selected = null;

        this.listElements.forEach( (listElement) => {
            if (listElement.domElement.classList.contains('selected')) {
                listElement.domElement.classList.remove('selected');
            }
        });

        if (this.tasks) {
            this.tasks.forEach( (task) => {
                task.clearSelected();
            });
        }
    }

    // Set the List's current 'selected' element
    setSelected(element) {
        this.selected = element;
    }

    // (This is called whenever the window is resized.)
    redrawEnhancedTaskBorders() {
        this.tasks.forEach( (task) => {
            task.drawEnhancedBorders();
        });
    }

}
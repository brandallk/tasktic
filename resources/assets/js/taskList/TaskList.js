import Category from './listElements/category/Category';
import Subcategory from './listElements/subcategory/Subcategory';
import Task from './listElements/task/Task';

export default class TaskList {
    
    constructor() {
        this.DOMelement = document.querySelector('.theList');
        this.categories = this.getCategories();
        this.subcategories = this.getSubcategories();
        this.tasks = this.getTasks();
        this.listElements = this.getListElements();
        this.selected = null;
    }

    getCategories() {
        const categories = [];

        this.DOMelement.querySelectorAll('div.category.selectable').forEach( (category) => {
            categories.push(new Category(this, category));
        });

        return categories;
    }

    getSubcategories() {
        const subcategories = [];

        this.DOMelement.querySelectorAll('div.subcategory.selectable').forEach( (subcategory) => {
            subcategories.push(new Subcategory(this, subcategory));
        });

        return subcategories;
    }

    getTasks() {
        const tasks = [];

        this.DOMelement.querySelectorAll('div.task.selectable').forEach( (task) => {
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
    }

    clearSelected() {
        this.selected = null;

        this.listElements.forEach( (listElement) => {
            if (listElement.DOMelement.classList.contains('selected')) {
                listElement.DOMelement.classList.remove('selected');
            }
        });

        if (this.tasks) {
            this.tasks.forEach( (task) => {
                task.clearSelected();
            });
        }
    }

    setSelected(element) {
        this.selected = element;
    }

    redrawEnhancedTaskBorders() {
        this.tasks.forEach( (task) => {
            task.drawEnhancedBorders();
        });
    }

}
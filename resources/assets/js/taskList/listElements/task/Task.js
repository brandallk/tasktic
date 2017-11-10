import ListElement from '../ListElement';
import Deadline from './taskItems/Deadline';
import Link from './taskItems/Link';
import Detail from './taskItems/Detail';
import DropdownToggle from './DropdownToggle';

export default class Task extends ListElement {
    
    constructor(taskList, task) {
        super(taskList, task);
        this.taskDeadlines = this.getTaskDeadlines();
        this.taskLinks = this.getTaskLinks();
        this.taskDetails = this.getTaskDetails();
        this.taskItems = this.getTaskItems();
        this.enhancedBorders = this.DOMelement.querySelectorAll('canvas');
        this.dropdownToggle = new DropdownToggle(this);
        this.selected = null;
    }

    getTaskDeadlines() {
        const taskDeadlines = [];

        this.DOMelement.querySelectorAll('div.deadline.selectable').forEach( (taskDeadline) => {
            taskDeadlines.push(new Deadline(this, taskDeadline));
        });

        return taskDeadlines;
    }

    getTaskLinks() {
        const taskLinks = [];

        this.DOMelement.querySelectorAll('div.link.selectable').forEach( (taskLink) => {
            taskLinks.push(new Link(this, taskLink));
        });

        return taskLinks;
    }

    getTaskDetails() {
        const taskDetails = [];

        this.DOMelement.querySelectorAll('div.detail.selectable').forEach( (taskDetail) => {
            taskDetails.push(new Detail(this, taskDetail));
        });

        return taskDetails;
    }

    getTaskItems() {
        const taskItems = [].concat(
            this.taskDeadlines,
            this.taskLinks,
            this.taskDetails
        );
        
        return taskItems;
    }

    activate() {
        super.activate();
        this.drawEnhancedBorders();
        this.dropdownToggle.activate();
        this.activateTaskItems();
    }

    drawEnhancedBorders() {
        this.enhancedBorders.forEach( (canvas) => {
            if (canvas.getContext) {
                const ctx = canvas.getContext('2d');
                const taskDiv = canvas.parentElement;
                const width = taskDiv.clientWidth;
                const height = 5;
                ctx.canvas.width = width;
                ctx.canvas.height = height;
                ctx.fillStyle = '#697cae';
                
                ctx.beginPath();
                ctx.moveTo(0, height/2);
                ctx.quadraticCurveTo(width/2, 0, width, height/2);
                ctx.quadraticCurveTo(width/2, height, 0, height/2);
                ctx.fill();
            }
        });
    }

    activateTaskItems() {
        if (this.taskItems) {
            this.taskItems.forEach( (item) => {
                item.activate();
            });
        }
    }

    clearSelected() {
        this.selected = null;
        if (this.taskItems) {
            this.taskItems.forEach( (item) => {
                if (item.DOMelement.classList.contains('selected')) {
                    item.DOMelement.classList.remove('selected');
                }
            });
        }
    }

    setSelected(element) {
        this.selected = element;
    }

}
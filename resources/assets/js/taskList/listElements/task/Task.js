import ListElement from '../ListElement';
import Deadline from './taskItems/Deadline';
import Link from './taskItems/Link';
import Detail from './taskItems/Detail';
import DropdownToggle from './DropdownToggle';

export default class Task extends ListElement {
    
    constructor(taskList, task) {
        super(taskList, task);
        this.taskDeadlines   = this.getTaskDeadlines();
        this.taskLinks       = this.getTaskLinks();
        this.taskDetails     = this.getTaskDetails();
        this.taskItems       = this.getTaskItems();
        this.dropdownToggle  = new DropdownToggle(this);
        this.selected        = null;
        this.enhancedBorders = this.domElement.querySelectorAll('canvas');
        this.actions = [
            'createChild',
            'deleteSelf',
            'editSelf',
            'toggleCompletionStatus',
            'togglePriorityStatus'
        ];
        this.formModals = {
            createChild:             this.domElement.querySelector('.modal.item.create'),
            deleteSelf:              this.domElement.querySelector('.modal.task.delete'),
            editSelf:                this.domElement.querySelector('.modal.task.edit'),
            toggleCompletionStatus:  this.domElement.querySelector('.modal.task.status'),
            togglePriorityStatus:    this.domElement.querySelector('.modal.task.priority')
        };
    }

    getTaskDeadlines() {
        const taskDeadlines = [];

        this.domElement.querySelectorAll('div.deadline.selectable').forEach( (taskDeadline) => {
            taskDeadlines.push(new Deadline(this, taskDeadline));
        });

        return taskDeadlines;
    }

    getTaskLinks() {
        const taskLinks = [];

        this.domElement.querySelectorAll('div.link.selectable').forEach( (taskLink) => {
            taskLinks.push(new Link(this, taskLink));
        });

        return taskLinks;
    }

    getTaskDetails() {
        const taskDetails = [];

        this.domElement.querySelectorAll('div.detail.selectable').forEach( (taskDetail) => {
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
        this.makeDraggable();
        this.makeDroppable();
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

    makeDraggable() {
        this.domElement.addEventListener('dragstart', (event) => {
            event.dataTransfer.setData("text/plain", event.target.getAttribute("data-taskID"));
        });
    }

    makeDroppable() {
        const dropTargets = this.domElement.parentElement.querySelectorAll('.dropTarget');

        dropTargets.forEach( (dropTarget) => {

            dropTarget.addEventListener('dragover', (event) => {
                event.preventDefault();
                event.target.style.backgroundColor = '#86c7e6';
            });

            dropTarget.addEventListener('dragleave', (event) => {
                event.target.style.backgroundColor = 'transparent';
            });

            dropTarget.addEventListener('drop', (event) => {
                event.preventDefault();

                const data = event.dataTransfer.getData("text");
                const form = dropTarget.querySelector('form');
                const input = form.querySelector('input.draggedTaskID');
                input.setAttribute('value', data);
                form.submit();
            });

        });
    }

    clearSelected() {
        this.selected = null;
        if (this.taskItems) {
            this.taskItems.forEach( (item) => {
                if (item.domElement.classList.contains('selected')) {
                    item.domElement.classList.remove('selected');
                }
            });
        }
    }

    setSelected(element) {
        this.selected = element;
    }

}
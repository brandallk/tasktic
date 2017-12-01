import MainMenu from './mainMenu/MainMenu';
import TaskList from './taskList/TaskList';
import ListIndex from './listIndex/ListIndex';

if (document.querySelector('div.main-menu')) {
    const mainMenu = new MainMenu();
    mainMenu.activate();
}

if (document.querySelector('.theList')) {
    const taskList = new TaskList();
    taskList.activate();

    window.onresize = taskList.redrawEnhancedTaskBorders.bind(taskList);
}

if(document.querySelector('ul.taskListIndex')) {
    const listIndex = new ListIndex();
    listIndex.activate();
}
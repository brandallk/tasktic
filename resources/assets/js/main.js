import MainMenu from './mainMenu/MainMenu';
import TaskList from './taskList/TaskList';

if (document.querySelector('div.main-menu')) {
    const mainMenu = new MainMenu();
    mainMenu.activate();
}

if (document.querySelector('.theList')) {
    const taskList = new TaskList();
    taskList.activate();

    window.onresize = taskList.redrawEnhancedTaskBorders.bind(taskList);
}
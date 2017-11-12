import MainMenu from './mainMenu/MainMenu';
import TaskList from './taskList/TaskList';

const mainMenu = new MainMenu();
const taskList = new TaskList();

mainMenu.activate();
taskList.activate();

window.onresize = taskList.redrawEnhancedTaskBorders.bind(taskList);

(function() {
    const toggleControls = document.querySelectorAll('span.task-toggle');

    toggleControls.forEach(function(toggler) {
        toggler.addEventListener('click', function(event) {
            const icon = toggler.querySelector('i');
            const task = toggler.parentElement;
            const taskItems = task.querySelectorAll('div.selectable');

            icon.classList.toggle('fa-caret-down');
            icon.classList.toggle('fa-caret-up');

            toggler.classList.toggle('down');
            toggler.classList.toggle('up');

            taskItems.forEach(function(item) {
                item.classList.toggle('hidden');
            });
        });
    });
})();

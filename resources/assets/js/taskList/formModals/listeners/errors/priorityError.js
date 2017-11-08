
(function() {

    const priorityErrorModal = document.querySelector('div.modal.error-priorityError');
    const priorityErrorCancel = priorityErrorModal.querySelector('.aknowledge.btn');

    priorityErrorCancel.addEventListener('click', function() {
        priorityErrorModal.classList.toggle('hidden');
    });

})();

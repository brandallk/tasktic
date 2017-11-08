
(function() {

    const editErrorModal = document.querySelector('div.modal.error-editError');
    const editErrorCancel = editErrorModal.querySelector('.aknowledge.btn');

    editErrorCancel.addEventListener('click', function() {
        editErrorModal.classList.toggle('hidden');
    });

})();


(function() {

    const statusErrorModal = document.querySelector('div.modal.error-statusError');
    const statusErrorCancel = statusErrorModal.querySelector('.aknowledge.btn');

    statusErrorCancel.addEventListener('click', function() {
        statusErrorModal.classList.toggle('hidden');
    });

})();

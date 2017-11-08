
(function() {

    const deleteErrorModal = document.querySelector('div.modal.error-deleteError');
    const deleteErrorCancel = deleteErrorModal.querySelector('.aknowledge.btn');

    deleteErrorCancel.addEventListener('click', function() {
        deleteErrorModal.classList.toggle('hidden');
    });

})();

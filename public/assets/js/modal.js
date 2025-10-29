$(document).ready(function() {
    function toggleModal(modalId) {
        const $body = $('body');
        const $modal = $(`#${modalId}`);

        $modal.toggleClass('opacity-0');
        $modal.toggleClass('pointer-events-none');
        $body.toggleClass('modal-active');
    }

    $(document).on('click', `[data-modal-open]`, function(event) {
        event.preventDefault();
        const modalId = $(this).data('modal-open');
        console.log('Opening modal:', modalId);
        toggleModal(modalId);
    });

    $(document).on('click', '.modal-overlay', function() {
        const modalId = $(this).closest('.modal').attr('id');
        toggleModal(modalId);
    });

    $(document).on('click', '.modal-close', function() {
        const modalId = $(this).closest('.modal').attr('id');
        toggleModal(modalId);
    });

    $(document).on('click', '[data-modal-close]', function() {
        const modalId = $(this).data('modal-close');
        toggleModal(modalId);
    });

    $(document).on('keydown', function(evt) {
        const isEscape = (evt.key === "Escape" || evt.key === "Esc" || evt.keyCode === 27);
        if (isEscape && $('body').hasClass('modal-active')) {
            $('.modal').not('.opacity-0').each(function() {
                const modalId = $(this).attr('id');
                toggleModal(modalId);
            });
        }
    });
});
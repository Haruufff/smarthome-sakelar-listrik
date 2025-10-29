const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function() {
    $(document).on('click', '.open-switch-modal', function() {
        const switchId = $(this).data('switch-id');
        const switchName = $(this).data('switch-name');

        $('#switch-id').val(switchId);
        $('#switch-name').val(switchName);
        toggleModal('switchModal');
    });

    $(document).on('submit', '#updateSwitchForm', function (e) {
        e.preventDefault();

        const switchId = $('#switch-id').val();

        if(!switchId) {
            showAlert('switch-alert-message', 'Invalid switch ID', 'error');
            return;
        }

        const formData = {
            name: $('#switch-name').val()
        };

        const url = `/switches/update-name-switch/${switchId}`;

        console.log('Sending request to:', url);
        console.log('Form data:', formData);

        $.ajax({
            url: url,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(formData),
            success: function(response) {
                console.log('Success: ', response.message);

                $(`#switch-name-${switchId}`).text(formData.name);
                $(`#open-switch-modal[data-switch-id="${switchId}"]`).data('switch-name', formData.name);

                clearErrors();
                showAlert('switch-alert-message', response.message, 'success');

                setTimeout(() => {
                    toggleModal('switchModal');
                }, 1000);
            },
            error: function(xhr, status, error) {
                console.log('Error updating switch : ', error);
                console.log('Response: ', xhr.responseText);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    clearErrors();

                    for (const field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            $(`#${field}-error`).text(errors[field][0]);
                        }
                    }
                    showAlert('switch-alert-message', 'Please fix the errors below.', 'error');
                } else {
                    showAlert('switch-alert-message', 'Failed to update switch. Please try again.', 'error');
                }
            }
        });
    });
});

function showAlert(elementId, message, type) {
    const $alertElement = $(`#${elementId}`);

    if ($alertElement.length === 0) return;

    const alertClass = type === 'error' 
        ? 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded' 
        : 'bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded';

    $alertElement.html(`
        <div class="${alertClass}" role="alert">
            <span class="block sm:inline">${message}</span>
        </div>
    `);

    setTimeout(() => {
        $alertElement.html('');
    }, 5000);
}

function clearErrors() {
    $('#name-error').text('');
}

function toggleModal(modalId) {
    const $body = $('body');
    const $modal = $(`#${modalId}`);

    $modal.toggleClass('opacity-0');
    $modal.toggleClass('pointer-events-none');
    $body.toggleClass('modal-active');
}
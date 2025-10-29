$(document).on('submit', '#updateConnectionForm', function(e) {
    e.preventDefault();

    const formData = {
        ssid: $('#ssid').val(),
        ssid_pass: $('#ssid_pass').val(),
    };

    $.ajax({
        url: '/profile/update-connection',
        type: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(formData),
        success: function(response) {
            console.log('Success: ', response.message);
            console.log('Connection updated: ', response.user);

            $('#ssid').val(response.data.ssid);
            $('#ssid_pass').val(response.data.ssid_pass);

            $('#display-ssid').val(formData.ssid);
            $('#display-ssid-pass').val(formData.ssid_pass);

            clearErrors();
            setTimeout(() => {
                toggleModal('connectionModal');
            }, 1000);
        },
        error: function(xhr, status, error) {
            console.error('Error updating connection: ', error);
            console.error('Response: ', xhr.responseText);

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                clearErrors();
                
                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        $(`#${field}-error`).text(errors[field][0]);
                    }
                }
                
                showAlert('connection-alert-message', 'Please fix the errors below.', 'error');
            } else {
                showAlert('connection-alert-message', 'Failed to update connection. Please try again.', 'error');
            }
        }
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
    $('#ssid-error').text('');
    $('#ssid-pass-error').text('');
    $('#connection-alert-message').html('');
}

function toggleModal(modalId) {
    const $body = $('body');
    const $modal = $(`#${modalId}`);

    $modal.toggleClass('opacity-0');
    $modal.toggleClass('pointer-events-none');
    $body.toggleClass('modal-active');
}
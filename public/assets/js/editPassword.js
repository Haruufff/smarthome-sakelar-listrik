$(document).on('submit', '#updatePasswordForm', function (e) {
    e.preventDefault();

    const formData = {
        current_password: $('#current-password').val(),
        new_password: $('#new-password').val(),
        new_password_confirmation: $('#new-password-confirmation').val()
    };

    $.ajax({
        url: '/profile/update-password',
        type: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(formData),
        success: function(response) {
            console.log('Success: ', response.message);
            console.log('Password updated: ', response.user);

            $('#updatePasswordForm')[0].reset();

            showAlert('password-alert-message', response.message, 'success');

            clearErrors();
            setTimeout(() => {
                toggleModal('passwordModal');
                $('#password-alert-message').html('');
            }, 2000);
        },
        error: function(xhr, status, error) {
            console.error('Error updating password: ', error);
            console.error('Response: ', xhr.responseText);

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                clearErrors();

                const fieldMapping = {
                    'current_password': 'current-password',
                    'new_password': 'new-password',
                    'new_password_confirmation': 'new-password-confirmation'
                };

                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        const frontendField = fieldMapping[field] || field;
                        $(`#${frontendField}-error`).text(errors[field][0]);
                    }
                }

                showAlert('password-alert-message', 'Please fix the errors below.', 'error');
            } else {
                showAlert('password-alert-message', 'Failed to update password. Please try again.', 'error');
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
    $('#current-password-error').text('');
    $('#new-password-error').text('');
    $('#new-password-confirmation-error').text('');
    $('#password-alert-message').html('');
}

function toggleModal(modalId) {
    const $body = $('body');
    const $modal = $(`#${modalId}`);

    $modal.toggleClass('opacity-0');
    $modal.toggleClass('pointer-events-none');
    $body.toggleClass('modal-active');
}
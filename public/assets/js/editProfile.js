const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).on('submit', '#updateProfileForm', function(e) {
    e.preventDefault();

    const formData = {
        name: $('#name').val(),
        username: $('#username').val(),
        email: $('#email').val()
    };

    $.ajax({
        url: '/profile/update-profile',
        type: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(formData),
        success: function(response) {
            console.log('Success: ', response.message);
            console.log('User updated: ', response.user);

            $('#display-name').val(formData.name);
            $('#display-username').val(formData.username);
            $('#display-email').val(formData.email);

            clearErrors();
            setTimeout(() => {
                toggleModal('profileModal');
            }, 1000);
        },
        error: function(xhr, status, error) {
            console.error('Error updating profile: ', error);
            console.error('Response: ', xhr.responseText);

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                clearErrors();
                
                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        $(`#${field}-error`).text(errors[field][0]);
                    }
                }
                
                showAlert('profile-alert-message', 'Please fix the errors below.', 'error');
            } else {
                showAlert('profile-alert-message', 'Failed to update profile. Please try again.', 'error');
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
    $('#name-error').text('');
    $('#username-error').text('');
    $('#email-error').text('');
    $('#profile-alert-message').html('');
}

function toggleModal(modalId) {
    const $body = $('body');
    const $modal = $(`#${modalId}`);

    $modal.toggleClass('opacity-0');
    $modal.toggleClass('pointer-events-none');
    $body.toggleClass('modal-active');
}
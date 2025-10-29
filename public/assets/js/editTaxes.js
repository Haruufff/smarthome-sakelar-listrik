const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function() {
    $(document).on('click', '.open-taxes-modal', function() {
        const taxesId = $(this).data('taxes-id');
        const categoryTaxId = $(this).data('category-tax-id');

        $('#taxes-id').val(taxesId);
        $('#category-tax-id').val(categoryTaxId);
        toggleModal('taxesModal');
    });
    $(document).on('submit', '#updateTaxesForm', function (e) {
        e.preventDefault();

        const taxesId = $('#taxes-id').val()

        if (!taxesId) {
            showAlert('taxes-alert-message', 'Invalid taxes ID.', 'error');
            return;
        }

        const formData = {
            category_tax_id: $('#category-tax-id').val()
        };

        const url = `/monitoring/realtime/update-taxes/${taxesId}`;

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
                console.log('success: ', response.message);

                $('#display-taxes').text($('#category-tax-id option:selected').text());

                clearErrors();
                showAlert('taxes-alert-message', response.message, 'success');

                setTimeout(() => {
                    toggleModal('taxesModal')
                }, 1000);
            },
            error: function(xhr, status, error) {
                console.log('Error updating taxes: ', error);
                console.log('Response: ', xhr.responseText);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    clearErrors();

                    for (const field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            $(`#${field}-error`).text(errors[field][0]);
                        }
                    }

                    showAlert('taxes-alert-message', 'Please fix the errors below.', 'error');
                } else {
                    showAlert('taxes-alert-message', 'Failed to tax profile. Please try again.', 'error');
                }
            }
        });
    });
})

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
    $('#taxes-error').text('');
}

function toggleModal(modalId) {
    const $body = $('body');
    const $modal = $(`#${modalId}`);

    $modal.toggleClass('opacity-0');
    $modal.toggleClass('pointer-events-none');
    $body.toggleClass('modal-active');
}
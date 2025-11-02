$(document).ready(function() {
    $(document).on('click', '[data-action="logout"]', function(event) {
        event.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/logout',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Logout successful:', response);
                window.location.href = '/';
            },
            error: function(xhr, status, error) {
                console.error('Logout failed:', { status, error });
                alert('An error occurred during logout. Please try again.');
            }
        });
    });
});
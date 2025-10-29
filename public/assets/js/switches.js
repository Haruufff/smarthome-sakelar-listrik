$(document).ready(function() {
    const $switches = $('.switch-checkbox');

    $switches.on('change', function() {
        const $this = $(this);
        const switchId = $this.data('id');
        const isChecked = $this.is(':checked');
        const $bgSwitch = $(`#bg-${switchId}`);
        const $dotSwitch = $(`#dot-${switchId}`);

        if (isChecked) {
            $bgSwitch.removeClass('bg-red-300').addClass('bg-green-300');
            $dotSwitch.removeClass('left-2').addClass('left-11');
        }
        else {
            $bgSwitch.removeClass('bg-green-300').addClass('bg-red-300');
            $dotSwitch.removeClass('left-11').addClass('left-2');
        }

        const newActived = isChecked ? 1 : 0;
        const newState = isChecked ? 'LOW' : 'HIGH';
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        console.log(`Updating switch ${switchId}: ${newState}`);

        $.ajax({
            url: `/switches/update-switch/${switchId}`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                state_status: newState,
                is_actived: newActived
            }),
            success: function(response){
                console.log(`Success: `, response.message);
            },
            error: function(xhr, status, error) {
                console.error('Error updating switch: ', error);
                console.error('Response: ', xhr.responseText);

                $this.prop('checked', !isChecked);

                if (isChecked) {
                    $bgSwitch.removeClass('bg-green-300').addClass('bg-red-300');
                    $dotSwitch.removeClass('left-11').addClass('left-2');
                } else {
                    $bgSwitch.removeClass('bg-red-300').addClass('bg-green-300');
                    $dotSwitch.removeClass('left-2').addClass('left-11')
                }

                alert('Failed to update switch. Please try again.')
            }
        });
    });
});
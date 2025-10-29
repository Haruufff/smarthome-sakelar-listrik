$(document).ready(function() {
    function updateDisplays() {
        $.ajax({
            url: '/api/monitoring/realtime-latest-data',
            method: 'GET',
            success: function(data) {
                $('#energy-display').text(Number(data.energy).toFixed(2));
                $('#power-display').text(Number(data.power).toFixed(2));
                $('#voltage-display').text(Number(data.voltage).toFixed(2));
                $('#current-display').text(Number(data.current).toFixed(2));
                $('#frequency-display').text(Number(data.frequency).toFixed(2));
                $('#cost-display').text(formatRupiah(data.cost));
            },
            error: function(err) {
                console.error('Error fetching data', err);
            }
        });
}

    function formatRupiah(cost) {
      return new Intl.NumberFormat('id-ID', {
          minimumFractionDigits: 0,
          maximumFractionDigits:  0
      }).format(cost);
}

    updateDisplays();
    setInterval(updateDisplays, 30000);
});
$(document).ready(function() {
    const config = window.monitoringConfig || {};
    console.log('Realtime Config: ', config);

    let realtimeChart = null;

    if(config.realtimeMonitoringEnable) {
        realtimeChart = new RealtimeMonitoringChart(
            'RealtimeMonitoringChart',
            config.realtimeApiUrl
        );
    }
});
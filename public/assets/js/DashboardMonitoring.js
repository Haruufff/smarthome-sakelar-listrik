$(document).ready(function() {
    const config = window.monitoringConfig || {};
    console.log('Dasboard Config: ', config);

    let priceChart = null;

    if (config.monthlyPriceEnable) {
        priceChart = new MonthlyPriceChart(
            'MonthlyPriceChart',
            config.monthlyPriceApiUrl
        );
    }
})
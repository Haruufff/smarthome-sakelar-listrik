$(document).ready(function() {
    const config = window.monitoringConfig || {};
    console.log('Config: ', config);

    let energyChart = null;
    let priceChart = null;
    let priceChartInitialized = false;

    if (config.monthlyAverageEnable) {
        energyChart = new MonthlyAverageChart(
            'MonthlyAverageChart',
            config.monthlyAverageApiUrl
        );
    }

    $('#chartTypeSelector').on('change', function() {
        const selectedType = $(this).val();
        console.log('Switching to:', selectedType);
        
        const $energyChart = $('#MonthlyAverageChart');
        const $priceChart = $('#MonthlyPriceChart');
        
        if (selectedType === 'energy') {
            $priceChart.removeClass('opacity-100').addClass('opacity-0');
            setTimeout(() => {
                $priceChart.addClass('hidden');
                $energyChart.removeClass('hidden');
                setTimeout(() => {
                    $energyChart.removeClass('opacity-0').addClass('opacity-100');
                    if (energyChart && energyChart.chart) {
                        energyChart.chart.windowResized();
                    }
                }, 50);
            }, 300);
            
        } else if (selectedType === 'price') {
            $energyChart.removeClass('opacity-100').addClass('opacity-0');
            setTimeout(() => {
                $energyChart.addClass('hidden');
                $priceChart.removeClass('hidden');
                
                setTimeout(() => {
                    $priceChart.removeClass('opacity-0').addClass('opacity-100');

                    if (!priceChartInitialized && config.monthlyPriceEnable) {
                        console.log('Initializing price chart');
                        priceChart = new MonthlyPriceChart(
                            'MonthlyPriceChart',
                            config.monthlyPriceApiUrl
                        );
                        priceChartInitialized = true;
                    } else if (priceChart && priceChart.chart) {
                        priceChart.chart.windowResized();
                    }
                }, 50);
            }, 300);
        }
    });

    if (config.monthDatas && config.monthDatas.length > 0) {
        initializeMonthlyChart(
            config.monthDatas,
            config.monthlyApiUrl
        );
    }
});
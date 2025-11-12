class RealtimeMonitoringChart {
    constructor(elementId, apiUrl) {
        this.elementId = elementId;
        this.apiUrl = apiUrl;
        this.chart = null;
        this.init();
    }

    init() {
        const options = {
            series: [{
                name: 'Energy (kWh)',
                data: []
            }],

            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                }
            },

            dataLabels: {
                enabled: false
            },

            stroke: {
                curve: 'smooth',
                width: 3
            },

            fill: {
              type: "gradient",
                gradient: {
                    opacityFrom: 0.55,
                    opacityTo: 0,
                    shade: "#1C64F2",
                    gradientToColors: ["#1C64F2"],
                },
            },

            xaxis: {
                categories: [],
                title: {
                    text: 'Time',
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold'
                    }
                },
                labels: {
                    style: {
                        fontSize: '11px'
                    }
                }
            },

            noData: {
                text: 'Loading..'
            },

            yaxis: {
                title: {
                    text: 'Energy (kWh)',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                labels: {
                    formatter: function (val) {
                        return val ? val.toFixed(2) + " kWh" : "0 kWh";
                    },
                    style: {
                        fontSize: '11px'
                    }
                }
            },

            tooltip: {
                x: {
                    show: true
                },
                y: {
                    formatter: function(val) {
                        return val ? val.toFixed(2) + " kWh" : "0 kWh";
                    },
                    title: {
                        formatter: () => 'Energy:'
                    }
                }
            }
        };


        this.chart = new ApexCharts(
            document.querySelector(`#${this.elementId}`),
            options
        );

        this.chart.render();
        this.loadData();
        this.startAutoRefresh();
    }

    loadData() {
        $.ajax({
            url: this.apiUrl,
            method: 'GET',
            dataType: 'json',
            success: (data) => {
                this.chart.updateOptions({
                    series: [{
                        name: 'Energy (kWh)',
                        data: data.energy || []
                    }],
                });
                console.log('Chart updated with', data.energy.length, 'data points');
            },
            error: (xhr, status, error) => {
                console.error('Error loading chart data:', error);
            }
        });
    }

    startAutoRefresh() {
        setInterval(() => {
            this.loadData();
        }, 60000);
    }
}
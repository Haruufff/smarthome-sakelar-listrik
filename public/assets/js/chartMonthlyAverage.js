class MonthlyAverageChart {
    constructor(elementId, apiUrl) {
        this.elementId = elementId;
        this.apiUrl = apiUrl;
        this.chart = null;
        this.init();
    }

    init() {
        const options = {
            series: [{
                name: 'Average Energy (KwH)',
                data: []
            }],

            chart: {
                height: 300,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },

            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },

            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(2) + ' KwH';
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"],
                    fontWeight: 'bold'
                }
            },

            xaxis: {
                categories: [],
                position: 'bottom',
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold'
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#e0e0e0'
                },
                axisTicks: {
                    show: true,
                    color: '#e0e0e0'
                }
            },

            noData: {
                text: 'Loading...'
            },

            yaxis: {
                title: {
                    text: 'Energy (kWh)',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#333'
                    }
                },
                labels: {
                    formatter: function (val) {
                        return val.toFixed(1) + " kWh";
                    },
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            colors: ['#008FFB'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toFixed(2) + " kWh";
                    }
                },
            }
        }

        this.chart = new ApexCharts(document.querySelector(`#${this.elementId}`), options);
        this.chart.render();
        this.loadData();
        this.startAutoRefresh();
    }

    loadData() {
        $.ajax({
            url: this.apiUrl,
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (data) => {
                this.chart.updateOptions({
                    series: [{
                        name: 'Average Energy',
                        data: data.energy
                    }],

                    xaxis: {
                        categories: data.datetime
                    }
                });
            },
            error: (xhr, status, error) => {
                console.error('Error loading monthly average data: ', error);
                console.error('Status: ', status);
                console.error('Response: ', xhr.responseText);
                console.error('Status Code: ', xhr.status);

                this.chart.updateOptions({
                    noData: {
                        text: 'No data available'
                    }
                });
            }
        });
    }

    startAutoRefresh() {
        setInterval(() => {
            this.loadData();
        }, 300000);
    }
}
class MonthlyDataChart {
    constructor(year, month, apiBaseUrl) {
        this.year = year;
        this.month = month;
        this.apiBaseUrl = apiBaseUrl;
        this.chartId = `chart${year}-${month}`;
        this.chart = null;
        this.init();
    }

    init() {
        if ($(`#${this.chartId}`).length === 0) {
            console.error(`Chart element #${this.chartId} not found`)
        }

        const options = {
            chart: {
                height: 200,
                type: 'area',
                fontFamily: 'Inter, sans-serif',
                zoom: {
                    enabled: false,
                },
                dropShadow: {
                    enabled: false
                },
                toolbar: {
                    show: false,
                },
            },

            tooltip: {
                enabled: true,
                x: {
                    show: false
                },
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

            dataLabels: {
                enabled: false
            },

            stroke: {
                width: 6,
            },

            grid: {
                show: false,
            },

            noData: {
                text: 'Loading...'
            },

            yaxis: {
                title: 'Energy (KwH)',
                rotate: -90,
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif',
                    fontWeight: 600,
                    cssClass: 'Apexchart-yaxis-title'
                }
            },

            xaxis: {
                title: {
                    text: 'Date'
                },
                categories: []
            },

            series: []
        };

        this.chart = new ApexCharts(
            document.querySelector(`#${this.chartId}`),
            options
        );

        this.chart.render();
        this.loadData();
        this.startAutoRefresh()
    }

    loadData() {
        const paddedMonth = String(this.month).padStart(2, '0');
        const url = `${this.apiBaseUrl}/history/${this.year}-${paddedMonth}`;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: (data) => {
                this.chart.updateOptions({
                    series: [{
                        name: 'Energy (kWh)',
                        data: data.energy
                    }],

                    xaxis: {
                        title: {
                            text: 'Date'
                        },
                        categories: data.datetime
                    },

                    responsive: [{
                        breakpoint: 480,
                        options: {
                            xaxis: {
                                range: 7
                            }
                        }
                    }],
                });
            },
            error: (xhr, status, error) => {
                console.error(`Error loading data ${self.year}-${self.month}:`, error);
                console.error('Status: ', status);
                console.error('HTTP Status:', xhr.status);
                console.error('Response:', xhr.responseText);

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

function initializeMonthlyChart(monthdata, apiBaseUrl) {
    if (!monthdata || monthdata.length === 0) {
        console.warn('No Month Data Provided');
        return;
    }

    console.log('Initializing monthly charts for : ', monthdata);

    $.each(monthdata, (index, month) => {
        new MonthlyDataChart(month.year, month.month, apiBaseUrl);
    });
}
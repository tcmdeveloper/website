import ApexCharts from 'apexcharts';

document.addEventListener('DOMContentLoaded', () => {
    const chartEl = document.querySelector('#timeline-chart');

    if (!chartEl || !window.heatmapData) {
        return;
    }

    const chart = new ApexCharts(chartEl, {
        chart: {
            type: 'heatmap',
            height: 130,
            toolbar: {
                show: false
            }
        },

        legend: {
            show: false
        },

        series: window.heatmapData,

        dataLabels: {
            enabled: true
        },

        plotOptions: {
            heatmap: {
                radius: 3,

                colorScale: {
                    ranges: [
                        {
                            from: 0,
                            to: 0,
                            color: '#f3f4f6'
                        },
                        {
                            from: 1,
                            to: 2,
                            color: '#fecaca'
                        },
                        {
                            from: 3,
                            to: 5,
                            color: '#ef4444'
                        },
                        {
                            from: 6,
                            to: 999,
                            color: '#991b1b'
                        }
                    ]
                }
            }
        }
    });

    chart.render();
});
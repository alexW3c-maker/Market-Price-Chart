const fetchData = async (days, ajaxUrl) => {
    const response = await fetch(`${ajaxUrl}?days=${days}`);
    const data = await response.json();
    return data.map(item => {
        return { date: new Date(item.date), value: parseFloat(item.value) };
    });
};

const ajaxUrl = marketPriceChart.ajaxUrl;

const renderChart = (data, chartContainer) => {
    

    if (window.marketPriceChart && typeof window.marketPriceChart.destroy === 'function') {
        window.marketPriceChart.destroy();
    }

    const ctx = chartContainer.getContext('2d');
    const minDate = data.length > 0 ? data[0].date : null;
    const maxDate = data.length > 0 ? data[data.length - 1].date : null;

    window.marketPriceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.date),
            datasets: [{
                label: 'Market Price',
                data: data.map(item => item.value),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'll',
                        displayFormats: {
                            day: 'MMM D',
                            month: 'MMM YYYY'
                        }
                    },
                    ticks: {
                        min: minDate,
                        max: maxDate
                    },
                    distribution: 'linear'
                }]
            },
            plugins: {
                zoom: {
                    pan: {
                        enabled: true,
                        mode: 'x',
                        speed: 10
                    },
                    zoom: {
                        enabled: true,
                        mode: 'x',
                        sensitivity: 0.1
                    }
                }
            }
        }
    });
};

(async (fetchData, ajaxUrl, renderChart) => {
    const chartContainer = document.getElementById('market-price-chart-canvas');

    const init = async () => {
        const data = await fetchData(0, ajaxUrl);
        renderChart(data, chartContainer);
    };

    await Promise.all([init()]);
})(fetchData, ajaxUrl, renderChart);

const updateChart = async (days) => {
    const data = await fetchData(days, ajaxUrl);
    const chartContainer = document.getElementById('market-price-chart-canvas');

    renderChart(data, chartContainer);
};

document.querySelectorAll('#market-price-chart button').forEach(button => {
    button.addEventListener('click', () => {
        const days = button.getAttribute('data-days');
        updateChart(days);
    });
});
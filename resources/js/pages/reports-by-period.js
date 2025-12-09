import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const chartCanvas = document.getElementById('dailyActivityChart');

    if (chartCanvas && window.dailyActivityData) {
        const ctx = chartCanvas.getContext('2d');
        const data = window.dailyActivityData;

        // Gradient for line
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // Green-500
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('es-MX', { day: '2-digit', month: 'short' });
                }),
                datasets: [{
                    label: 'Registros',
                    data: data.map(item => item.count),
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointBorderColor: '#1f2937',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#F3F4F6',
                        bodyColor: '#D1D5DB',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        intersect: false,
                        mode: 'index'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9CA3AF', font: { family: "'Inter', sans-serif" } },
                        grid: { color: 'rgba(75, 85, 99, 0.1)', borderDash: [5, 5] },
                        border: { display: false }
                    },
                    x: {
                        ticks: { color: '#9CA3AF', maxRotation: 45, font: { family: "'Inter', sans-serif" } },
                        grid: { display: false },
                        border: { display: false }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
});

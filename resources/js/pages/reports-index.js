import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const chartCanvas = document.getElementById('participationChart');

    if (chartCanvas && window.participationData) {
        const ctx = chartCanvas.getContext('2d');
        const data = window.participationData;

        // Gradient for bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(139, 92, 246, 0.8)'); // Purple-500
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.2)');  // Indigo-600

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => {
                    const [year, month] = item.month.split('-');
                    const date = new Date(year, month - 1);
                    return date.toLocaleDateString('es-MX', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Equipos',
                    data: data.map(item => item.count),
                    backgroundColor: gradient,
                    borderColor: 'rgba(139, 92, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(139, 92, 246, 1)',
                    barThickness: 'flex',
                    maxBarThickness: 40
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
                        borderColor: 'rgba(75, 85, 99, 0.5)',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
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
                        ticks: { color: '#9CA3AF', font: { family: "'Inter', sans-serif" } },
                        grid: { display: false },
                        border: { display: false }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
});

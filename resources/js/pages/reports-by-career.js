import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const chartCanvas = document.getElementById('careersChart');

    if (chartCanvas && window.careerData) {
        const ctx = chartCanvas.getContext('2d');
        const careers = window.careerData;

        const colors = [
            'rgba(59, 130, 246, 0.8)', // blue-500
            'rgba(139, 92, 246, 0.8)', // purple-500
            'rgba(16, 185, 129, 0.8)', // green-500
            'rgba(245, 158, 11, 0.8)', // amber-500
            'rgba(239, 68, 68, 0.8)',  // red-500
            'rgba(236, 72, 153, 0.8)', // pink-500
            'rgba(14, 165, 233, 0.8)', // sky-500
            'rgba(34, 197, 94, 0.8)',  // emerald-500
            'rgba(168, 85, 247, 0.8)', // violet-500
            'rgba(251, 146, 60, 0.8)'  // orange-400
        ];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: careers.map(c => c.name),
                datasets: [{
                    data: careers.map(c => c.students_count),
                    backgroundColor: colors,
                    borderColor: 'rgba(31, 41, 55, 0)', // Transparent border for smoother look or match bg
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#9CA3AF',
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#F3F4F6',
                        bodyColor: '#D1D5DB',
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 4
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    }
});

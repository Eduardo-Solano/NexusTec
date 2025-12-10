import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    // Configuración Global Premium
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
    Chart.defaults.font.weight = '600';

    // Función para crear gradientes
    function createGradient(ctx, colorStart, colorEnd) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    }

    // 1. Gráfica de Equipos Registrados (Smart Timeline)
    const teamsCtx = document.getElementById('teamsChart');
    if (teamsCtx && window.dashboardData && window.dashboardData.teamsByDay) {
        const ctx = teamsCtx.getContext('2d');

        // --- PROCESAMIENTO DE DATOS INTELIGENTE ---
        // Obtener datos crudos del backend (inyectados vía window)
        const rawData = window.dashboardData.teamsByDay;

        // Generar los últimos 14 días para llenar huecos
        const labels = [];
        const dailyData = [];
        const accumulatedData = [];
        let currentSum = 0; // Podrías inicializar esto con un total histórico si lo tuvieras

        for (let i = 13; i >= 0; i--) {
            const d = new Date();
            d.setDate(d.getDate() - i);
            const dateStr = d.toISOString().split('T')[0]; // YYYY-MM-DD

            // Formato legible para el eje X (e.g., "05 Dic")
            const labelStr = d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
            labels.push(labelStr);

            // Buscar si hay datos para este día
            const dayRecord = rawData.find(r => r.date === dateStr);
            const count = dayRecord ? parseInt(dayRecord.count) : 0;

            dailyData.push(count);

            // Calcular acumulado
            currentSum += count;
            accumulatedData.push(currentSum);
        }

        // --- ESTILOS ---
        const barGradient = ctx.createLinearGradient(0, 0, 0, 300);
        barGradient.addColorStop(0, '#8B5CF6'); // Purple
        barGradient.addColorStop(1, 'rgba(139, 92, 246, 0.2)');

        new Chart(teamsCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Total Acumulado',
                        data: accumulatedData,
                        borderColor: '#38bdf8', // Sky Blue Neon
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: '#0ea5e9',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1',
                        fill: {
                            target: 'origin',
                            below: 'rgba(56, 189, 248, 0.05)' // Sutil brillo bajo la línea
                        }
                    },
                    {
                        type: 'bar',
                        label: 'Registros Diarios',
                        data: dailyData,
                        backgroundColor: barGradient,
                        borderColor: '#8B5CF6', // Purple Border
                        borderWidth: { top: 2, right: 1, left: 1, bottom: 0 },
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: { color: '#94a3b8', usePointStyle: true, boxWidth: 8 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            title: (items) => `📅 ${items[0].label}`,
                            label: (context) => {
                                const val = context.parsed.y;
                                return context.dataset.type === 'line'
                                    ? `📈 Total: ${val} equipos`
                                    : `🆕 Nuevos: +${val}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 10 }, maxRotation: 0 }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Diarios', color: '#8B5CF6', font: { size: 9 } },
                        grid: { color: 'rgba(255, 255, 255, 0.05)', borderDash: [5, 5] },
                        suggestedMax: Math.max(...dailyData) + 2 // Dar aire arriba
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { display: false } // Ocultar números del acumulado para limpieza
                    }
                }
            }
        });
    }

    // 2. Gráfica de Estudiantes por Carrera (Doughnut Neon)
    const careersCtx = document.getElementById('careersChart');
    if (careersCtx && window.dashboardData && window.dashboardData.studentsByCareerNames && window.dashboardData.studentsByCareerCounts) {
        new Chart(careersCtx, {
            type: 'doughnut',
            data: {
                labels: window.dashboardData.studentsByCareerNames,
                datasets: [{
                    data: window.dashboardData.studentsByCareerCounts,
                    backgroundColor: [
                        'rgba(139, 92, 246, 0.6)', // Purple
                        'rgba(236, 72, 153, 0.6)', // Pink
                        'rgba(59, 130, 246, 0.6)', // Blue
                        'rgba(16, 185, 129, 0.6)', // Emerald
                        'rgba(245, 158, 11, 0.6)'  // Amber
                    ],
                    borderColor: [
                        '#8B5CF6', '#EC4899', '#3B82F6', '#10B981', '#F59E0B'
                    ],
                    borderWidth: 2,
                    hoverOffset: 15,
                    borderRadius: 20, // Bordes redondeados en los segmentos
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 8,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 25,
                            font: { size: 12 }
                        }
                    }
                },
                cutout: '75%',
                layout: { padding: 20 },
                animation: { animateScale: true, animateRotate: true }
            }
        });
    }

    // 3. Gráfica de Eventos (Bar Chart Gradient)
    const eventsCtx = document.getElementById('eventsChart');
    if (eventsCtx && window.dashboardData && window.dashboardData.projectsByEventNames && window.dashboardData.projectsByEventCounts) {
        const ctx = eventsCtx.getContext('2d');
        const gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
        gradientBar.addColorStop(0, '#34D399'); // Emerald 400
        gradientBar.addColorStop(1, 'rgba(16, 185, 129, 0.2)'); // Emerald 500 transparent

        new Chart(eventsCtx, {
            type: 'bar',
            data: {
                labels: window.dashboardData.projectsByEventNames,
                datasets: [{
                    label: 'Equipos',
                    data: window.dashboardData.projectsByEventCounts,
                    backgroundColor: gradientBar,
                    borderColor: '#34D399', // Emerald Border
                    borderWidth: { top: 2, right: 1, left: 1, bottom: 0 },
                    borderRadius: { topLeft: 20, topRight: 20 },
                    barThickness: 50,
                    maxBarThickness: 60,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#E2E8F0',
                        padding: 12,
                        cornerRadius: 8,
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#64748b', precision: 0 },
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

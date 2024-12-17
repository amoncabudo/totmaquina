<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Mantenimiento</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Estadísticas de Mantenimiento</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8" role="alert">
                <strong class="font-bold">Error: </strong>
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!isset($error)): ?>
        <!-- Resumen General -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Total Mantenimientos</h3>
                <p class="text-4xl font-bold text-blue-600"><?= $stats['general']['total'] ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Pendientes</h3>
                <p class="text-4xl font-bold text-yellow-600"><?= $stats['general']['pending'] ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">En Progreso</h3>
                <p class="text-4xl font-bold text-blue-600"><?= $stats['general']['in_progress'] ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Completados</h3>
                <p class="text-4xl font-bold text-green-600"><?= $stats['general']['completed'] ?></p>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Gráfico por Tipo -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Mantenimientos por Tipo</h3>
                <canvas id="typeChart"></canvas>
            </div>

            <!-- Gráfico Mensual -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Mantenimientos por Mes</h3>
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Gráfico por Máquina -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Mantenimientos por Máquina</h3>
            <canvas id="machineChart"></canvas>
        </div>

        <!-- Tiempo Promedio -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Tiempo Promedio de Completado</h3>
            <p class="text-4xl font-bold text-purple-600">
                <?= round($stats['completion_time']['avg_completion_time'], 1) ?> días
            </p>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // Configuración de colores
        const colors = {
            blue: 'rgb(59, 130, 246)',
            green: 'rgb(16, 185, 129)',
            yellow: 'rgb(245, 158, 11)',
            red: 'rgb(239, 68, 68)',
            purple: 'rgb(139, 92, 246)'
        };

        // Gráfico por Tipo
        new Chart(document.getElementById('typeChart'), {
            type: 'pie',
            data: {
                labels: <?= json_encode($chartData['byType']['labels']) ?>,
                datasets: [{
                    data: <?= json_encode($chartData['byType']['data']) ?>,
                    backgroundColor: [colors.blue, colors.green],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                return `${label}: ${value} mantenimientos`;
                            }
                        }
                    }
                }
            }
        });

        // Gráfico Mensual
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($chartData['monthly']['labels']) ?>,
                datasets: [{
                    label: 'Mantenimientos',
                    data: <?= json_encode($chartData['monthly']['data']) ?>,
                    borderColor: colors.blue,
                    backgroundColor: colors.blue + '20',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw} mantenimientos`;
                            }
                        }
                    }
                }
            }
        });

        // Gráfico por Máquina
        new Chart(document.getElementById('machineChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartData['byMachine']['labels']) ?>,
                datasets: [{
                    label: 'Total',
                    data: <?= json_encode($chartData['byMachine']['data']) ?>,
                    backgroundColor: colors.blue
                }, {
                    label: 'Completados',
                    data: <?= json_encode($chartData['byMachine']['completed']) ?>,
                    backgroundColor: colors.green
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw} mantenimientos`;
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html> 
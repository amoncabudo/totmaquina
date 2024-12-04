<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Mantenimiento - Panel de Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Estadísticas de Mantenimiento</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Gráfico por Tipo -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Por Tipo de Mantenimiento</h2>
                <canvas id="typeChart"></canvas>
            </div>

            <!-- Gráfico por Frecuencia -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Por Frecuencia</h2>
                <canvas id="frequencyChart"></canvas>
            </div>

            <!-- Gráfico Mensual -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Mantenimientos por Mes</h2>
                <canvas id="monthlyChart"></canvas>
            </div>

            <!-- Gráfico por Máquina -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Por Máquina</h2>
                <canvas id="machineChart"></canvas>
            </div>
        </div>
    </main>

    <!-- Contenedor oculto para los datos -->
    <div id="stats-data" 
         data-type-stats='<?= json_encode($stats['by_type']) ?>'
         data-frequency-stats='<?= json_encode($stats['by_frequency']) ?>'
         data-monthly-stats='<?= json_encode($stats['by_month']) ?>'
         data-machine-stats='<?= json_encode($stats['by_machine']) ?>'
         class="hidden">
    </div>

    <script src="/js/main.js"></script>
    <script src="/js/flowbite.min.js"></script>
</body>
</html> 
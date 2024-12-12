<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Incidencias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">

</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-900">Historial de Incidencias</h1>
                <div class="w-full md:w-1/3">
                    <label for="machine-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Máquina
                    </label>
                    <select id="machine-select" class="w-full p-2 border rounded-lg">
                        <option value="">Seleccione una máquina</option>
                        <?php foreach ($machines as $machine): ?>
                            <option value="<?php echo htmlspecialchars($machine['id']); ?>">
                                <?php echo htmlspecialchars($machine['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Información de la máquina -->
            <div id="machine-info" class="mb-6 hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800">Total Incidencias</h3>
                        <p class="text-3xl font-bold text-blue-600" id="total-incidents">0</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-yellow-800">Pendientes</h3>
                        <p class="text-3xl font-bold text-yellow-600" id="pending-incidents">0</p>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-orange-800">En Proceso</h3>
                        <p class="text-3xl font-bold text-orange-600" id="in-progress-incidents">0</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800">Resueltas</h3>
                        <p class="text-3xl font-bold text-green-600" id="resolved-incidents">0</p>
                    </div>
                </div>
            </div>

            <!-- Contenido del historial -->
            <div id="history-content" class="overflow-x-auto">
                <p class="text-gray-600 text-center py-8">Seleccione una máquina para ver su historial de incidencias.</p>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="/js/main.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Fancybox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">

</head>
<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Historial de Incidencias</h1>

        <div class="mb-6">
            <label for="machine-select" class="block text-sm font-medium text-gray-700">Seleccionar Máquina</label>
            <select id="machine-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Seleccione una máquina</option>
                <?php foreach ($machines as $machine): ?>
                    <option value="<?= htmlspecialchars($machine['id']) ?>">
                        <?= htmlspecialchars($machine['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Información de la máquina -->
            <div id="machine-info" class="bg-white p-6 rounded-lg shadow-md">
                <!-- La información de la máquina se cargará aquí -->
            </div>

            <!-- Lista de incidencias -->
            <div id="incidents-list" class="md:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <!-- Las incidencias se cargarán aquí -->
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50"></div>

    <script src="/js/main.js"></script>
    <script src="/js/flowbite.min.js"></script>
    <script src="/js/bundle.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Mantenimiento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Historial de Mantenimiento</h1>

        <form id="maintenance-history-form" class="mb-6">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label for="machine-select" class="block text-sm font-medium text-gray-700">Seleccionar Máquina</label>
                    <select name="machine_id" id="machine-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione una máquina</option>
                        <?php foreach ($machines as $machine): ?>
                            <option value="<?= htmlspecialchars($machine['id']) ?>">
                                <?= htmlspecialchars($machine['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Consultar Historial
                    </button>
                </div>
            </div>
        </form>

        <div id="maintenance-history" class="bg-white rounded-lg shadow-md p-6">
            <!-- El historial se cargará aquí -->
        </div>
    </div>

    <!-- Toast container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50"></div>

    <script src="/js/main.js"></script>
    <script src="/js/flowbite.min.js"></script>
    <script src="/js/bundle.js"></script>
</body>
</html>
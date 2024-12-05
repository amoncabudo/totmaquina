<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Incidencias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="container mx-auto bg-white p-6 rounded shadow mt-20">
        <h1 class="text-2xl font-bold mb-6">Historial de Incidencias</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Selector de máquinas -->
        <div class="mb-6">
            <label for="machine-select" class="block text-sm font-medium text-gray-700 mb-2">Selecciona una máquina:</label>
            <select id="machine-select" name="machine" class="w-full border border-gray-300 rounded-md shadow-sm p-2">
                <option value="">-- Selecciona una máquina --</option>
                <?php foreach ($machines as $machine): ?>
                    <option value="<?= htmlspecialchars($machine['id']) ?>">
                        <?= htmlspecialchars($machine['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tabla de historial -->
        <div id="history-content">
            <p class="text-gray-600">Selecciona una máquina para ver su historial de incidencias.</p>
        </div>
    </div>
    <script src="/js/main.js"></script>
    <script src="/js/flowbite.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tot Màquina - Historial de Mantenimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="/main.css">
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">

</head>
<body class="bg-gray-100">

<?php include __DIR__ . "/layouts/navbar.php"; ?>

<main class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">Historial de Mantenimiento</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form id="maintenance-form" class="mb-8">
                <div class="mb-4">
                    <label for="machine-select" class="block text-lg font-medium text-gray-700 mb-2">Selecciona una máquina:</label>
                    <select id="machine-select" name="machine" class="w-full border border-gray-300 rounded-lg p-2">
                        <option value="">-- Selecciona una máquina --</option>
                        <?php foreach ($machines as $machine): ?>
                            <option value="<?= htmlspecialchars($machine['id']) ?>">
                                <?= htmlspecialchars($machine['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Consultar Historial
                </button>
            </form>

            <div id="machine-info" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Información de la Máquina</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p class="text-gray-700"><strong>Nombre:</strong> <span id="info-nombre"></span></p>
                    <p class="text-gray-700"><strong>Modelo:</strong> <span id="info-modelo"></span></p>
                    <p class="text-gray-700"><strong>Fabricante:</strong> <span id="info-fabricante"></span></p>
                    <p class="text-gray-700"><strong>Ubicación:</strong> <span id="info-ubicacion"></span></p>
                </div>
            </div>

            <div id="maintenance-history" class="hidden">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Registros de Mantenimiento</h2>
                <div id="history-content" class="space-y-4">
                    <!-- Los registros se insertarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</main>


<script src="/js/main.js"></script>
<script src="/js/flowbite.min.js"></script>

</body>
</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Máquinas Asignadas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-4 sm:p-8">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Mis Máquinas Asignadas</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Machine Card -->
            <?php foreach ($machines as $machine): ?>
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($machine["name"]) ?></h2>
                <p class="text-sm text-gray-600">Modelo: <?= htmlspecialchars($machine["model"]) ?></p>
                <p class="text-sm text-gray-600">Fabricante: <?= htmlspecialchars($machine["manufacturer"]) ?></p>
                <p class="text-sm text-gray-600">Estado: 
                    <span class="font-medium <?= $machine["status"] === "Activo" ? "text-green-600" : "text-red-600" ?>">
                        <?= htmlspecialchars($machine["status"]) ?>
                    </span>
                </p>
                <div class="mt-4 flex justify-between items-center">
                    <button onclick="window.location.href='/machine/<?= $machine["id"] ?>'"
                        class="bg-blue-800 text-white hover:bg-blue-900 font-medium rounded-lg text-sm px-4 py-2">
                        Ver Detalles
                    </button>
                    <button onclick="window.location.href='/machine/edit/<?= $machine["id"] ?>'"
                        class="bg-yellow-600 text-white hover:bg-yellow-700 font-medium rounded-lg text-sm px-4 py-2">
                        Editar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . "/layouts/footer.php"; ?>
</body>

</html>

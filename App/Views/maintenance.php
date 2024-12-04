<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Máquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-semibold text-center text-gray-800 mb-5">Selecciona una Máquina</h1>

        <!-- Taula de selecció de màquines estilada amb Tailwind -->
        <?php if (isset($machines) && is_array($machines) && count($machines) > 0): ?>
            <div class="overflow-x-auto bg-white shadow-lg rounded-lg mb-4">
                <table class="min-w-full text-center table-auto">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-gray-600">ID</th>
                            <th class="px-4 py-2 text-gray-600">Nom</th>
                            <th class="px-4 py-2 text-gray-600">Model</th>
                            <th class="px-4 py-2 text-gray-600">Fabricant</th>
                            <th class="px-4 py-2 text-gray-600">Ubicació</th>
                            <th class="px-4 py-2 text-gray-600">Data Instal·lació</th>
                            <th class="px-4 py-2 text-gray-600">Acció</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        <?php foreach ($machines as $machine): ?>
                            <tr class="border-b hover:bg-gray-50 cursor-pointer" onclick="toggleMachineDetails(<?php echo htmlspecialchars(json_encode($machine)); ?>)">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($machine['id']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($machine['name']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($machine['model']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($machine['manufacturer']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($machine['location']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($machine['installation_date']); ?></td>
                                <td class="px-4 py-2">
                                    <!-- Botó per veure el historial de la màquina -->
                                    <form action="history.php" method="GET" class="inline-block">
                                        <input type="hidden" name="machine_id" value="<?php echo htmlspecialchars($machine['id']); ?>">
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Veure Historial</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-700">No hi ha màquines disponibles</p>
        <?php endif; ?>

        <!-- Detalls de la màquina seleccionada -->
        <div id="machine-details" class="bg-white p-6 rounded-lg shadow-md mt-5 hidden">
            <h2 class="text-xl font-semibold mb-4">Detalls de la màquina seleccionada</h2>
            <p><strong>ID:</strong> <span id="machine-id"></span></p>
            <p><strong>Nom:</strong> <span id="machine-name"></span></p>
            <p><strong>Model:</strong> <span id="machine-model"></span></p>
            <p><strong>Fabricant:</strong> <span id="machine-manufacturer"></span></p>
            <p><strong>Ubicació:</strong> <span id="machine-location"></span></p>
            <p><strong>Data Instal·lació:</strong> <span id="machine-installation-date"></span></p>
            <p><strong>Coordenades:</strong> <span id="machine-coordinates"></span></p>
        </div>
    </div>

    <script>
        function toggleMachineDetails(machine) {
            // Mostrar o amagar la secció de detalls
            const detailsSection = document.getElementById('machine-details');

            // Omplir els detalls amb la informació de la màquina
            document.getElementById('machine-id').textContent = machine.id;
            document.getElementById('machine-name').textContent = machine.name;
            document.getElementById('machine-model').textContent = machine.model;
            document.getElementById('machine-manufacturer').textContent = machine.manufacturer;
            document.getElementById('machine-location').textContent = machine.location;
            document.getElementById('machine-installation-date').textContent = machine.installation_date;
            document.getElementById('machine-coordinates').textContent = machine.coordinates;

            // Mostrar la secció de detalls
            detailsSection.classList.remove('hidden');
        }
    </script>

    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.js"></script>
</body>
</html>

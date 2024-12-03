<!-- View: machines.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Máquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" rel="stylesheet" />
</head>
<?php include __DIR__ . "/layouts/navbar.php"; ?>
<body class="bg-gray-100">
    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-semibold text-center text-gray-800 mb-5">Selecciona una Máquina</h1>

        <!-- Mostrar error si hay -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mb-4">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p class="font-bold">Error</p>
                    <p><?php echo $error; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para seleccionar la máquina -->
        <form method="POST" action="history.php" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="machine_id" class="block text-gray-700 font-medium mb-2">Selecciona la máquina:</label>
                <select name="machine_id" id="machine_id" class="form-select block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Selecciona una máquina --</option>
                    <?php if (isset($machines) && count($machines) > 0): ?>
                        <?php foreach ($machines as $machine): ?>
                            <option value="<?php echo $machine['id']; ?>"><?php echo $machine['name']; ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay máquinas disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="btn bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Ver Incidencias</button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.js"></script>
</body>
</html>

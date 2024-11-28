<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Machine Inventory</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="css/main.css">
</head>

<body class="bg-gray-100 min-h-screen">

  <div class="max-w-7xl mx-auto p-8 mt-10 rounded-lg ">
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Inventario de Máquinas</h1>
    <div class="flex justify-end mb-4">
      <a href="addmachine" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        Crear Máquina
      </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <?php foreach ($machines as $machine): ?>
            <div class="machine-entry bg-gray-200 p-4 rounded-lg shadow-md mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold"><?php echo htmlspecialchars($machine['name']) ?></h2>
                        <p class="text-sm"> <?php echo htmlspecialchars($machine['model']) ?>, <?php echo htmlspecialchars($machine['manufacturer']) ?>, <?php echo htmlspecialchars($machine['location']) ?></p>
                    </div>
                    <a href="machinedetail/<?php echo htmlspecialchars($machine['id']); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-800 border border-transparent rounded-lg shadow-sm hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                        Ver Mas
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
  </div>
</body>
<script src="/js/flowbite.min.js"></script>
<script src="/js/bundle.js"></script>
<script src="/js/machineinv.js"></script>

</html>
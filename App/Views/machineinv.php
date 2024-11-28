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

 
    <!-- Modal para agregar máquina -->
    <div class="modal fade" id="addMachineModal" tabindex="-1" aria-labelledby="addMachineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMachineModalLabel">Agregar Máquina</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="/addmachine">
                        <div class="mb-3">
                            <label for="machineName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name" id="machineName" required>
                        </div>
                        <div class="mb-3">
                            <label for="machineModel" class="form-label">Modelo</label>
                            <input type="text" class="form-control" name="model" id="machineModel" required>
                        </div>
                        <div class="mb-3">
                            <label for="machineManufacturer" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" name="manufacturer" id="machineManufacturer" required>
                        </div>
                        <div class="mb-3">
                            <label for="machineLocation" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" name="location" id="machineLocation" required>
                        </div>
                        <div class="mb-3">
                            <label for="machineSerialNumber" class="form-label">Número de Serie</label>
                            <input type="text" class="form-control" name="serial_number" id="machineSerialNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="machineInstallationDate" class="form-label">Fecha de Instalación</label>
                            <input type="date" class="form-control" name="installation_date" id="machineInstallationDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="machinePhoto" class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="photo" id="machinePhoto">
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para abrir el modal de agregar máquina -->
    <button data-bs-toggle="modal" data-bs-target="#addMachineModal" class="btn btn-primary">
        Agregar Máquina
    </button>

</body>
<script src="/js/flowbite.min.js"></script>
<script src="/js/bundle.js"></script>
<script src="/js/machineinv.js"></script>

</html>
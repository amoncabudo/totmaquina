<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="max-w-7xl mx-auto p-8  rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Inventario de Máquinas</h1>
        <div class="flex justify-end mb-4">
            <!-- Button to open modal -->
            <div class="flex justify-end mb-4 space-x-4">
                <!-- Button to open Add Machine modal -->
                <button data-modal-target="machine-modal" data-modal-toggle="machine-modal"
                    class="bg-gray-800 text-white hover:bg-gray-700 focus:ring-4 focus:ring-gray-600 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Añadir Máquina
                </button>

                <!-- Button to open Add Machine CSV modal -->
                <button data-modal-target="csv-upload-modal" data-modal-toggle="csv-upload-modal"
                    class="bg-gray-800 text-white hover:bg-gray-700 focus:ring-4 focus:ring-gray-600 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 3a1 1 0 0 1 .78.375l4 5a1 1 0 1 1-1.56 1.25L13 6.85V14a1 1 0 1 1-2 0V6.85L8.78 9.626a1 1 0 1 1-1.56-1.25l4-5A1 1 0 0 1 12 3ZM9 14v-1H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-4v1a3 3 0 1 1-6 0Zm8 2a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H17Z" clip-rule="evenodd" />
                    </svg>

                    Añadir Máquina CSV
                </button>
            </div>
            <!-- Modal -->
            <div id="machine-modal" tabindex="-1" aria-hidden="true"
                class="hidden fixed top-6 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-y-auto h-full">
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="bg-white rounded-lg shadow">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-300">
                            <h2 class="text-lg font-semibold text-gray-900">Añadir Máquina</h2>
                            <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="machine-modal" aria-label="Cerrar modal">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-6">
                            <form action="/addmachine" method="POST" enctype="multipart/form-data">
                                <div class="grid gap-4">
                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-900">Nombre</label>
                                        <input type="text" id="name" name="name" placeholder="Enter name" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <!-- Model -->
                                    <div>
                                        <label for="model" class="block text-sm font-medium text-gray-900">Modelo</label>
                                        <input type="text" id="model" name="model" placeholder="Enter model" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <!-- Manufacturer -->
                                    <div>
                                        <label for="manufacturer" class="block text-sm font-medium text-gray-900">Fabricante</label>
                                        <input type="text" id="manufacturer" name="manufacturer" placeholder="Enter manufacturer" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <!-- Location -->
                                    <div>
                                        <label for="location" class="block text-sm font-medium text-gray-900">Ubicación</label>
                                        <input type="text" id="location" name="location" placeholder="Enter location" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <!-- Coordinates -->
                                    <div>
                                        <label for="coordinates" class="block text-sm font-medium text-gray-900">Coordenadas</label>
                                        <input type="text" id="coordinates" name="coordinates" placeholder="Enter coordinates" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <!-- Serial Number -->
                                    <div>
                                        <label for="serial_number" class="block text-sm font-medium text-gray-900">Número de Serie</label>
                                        <input type="text" id="serial_number" name="serial_number" placeholder="Enter serial number" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <!-- Installation Date -->
                                    <div>
                                        <label for="installation_date" class="block text-sm font-medium text-gray-900">Fecha de Instalación</label>
                                        <input type="date" id="installation_date" name="installation_date" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <!-- Image -->
                                    <div>
                                        <label for="photo" class="block text-sm font-medium text-gray-900">Imagen</label>
                                        <input type="file" id="photo" name="photo"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-800">Cancel</button>
                                        <button type="submit" class="bg-blue-800 text-white px-4 py-2 rounded-md hover:bg-blue-800">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div id="machine-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-10 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-y-auto h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <h2 class="text-lg font-semibold text-gray-900">Añadir Máquina</h2>
                        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="machine-modal" aria-label="Cerrar modal">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        <form action="/addmachine" method="POST" enctype="multipart/form-data">
                            <div class="grid gap-4">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-900">Nombre</label>
                                    <input type="text" id="name" name="name" placeholder="Enter name" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Model -->
                                <div>
                                    <label for="model" class="block text-sm font-medium text-gray-900">Modelo</label>
                                    <input type="text" id="model" name="model" placeholder="Enter model" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Manufacturer -->
                                <div>
                                    <label for="manufacturer" class="block text-sm font-medium text-gray-900">Fabricante</label>
                                    <input type="text" id="manufacturer" name="manufacturer" placeholder="Enter manufacturer" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Location -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-900">Ubicación</label>
                                    <input type="text" id="location" name="location" placeholder="Enter location" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Coordinates -->
                                <div>
                                    <label for="coordinates" class="block text-sm font-medium text-gray-900">Coordenadas</label>
                                    <input type="text" id="coordinates" name="coordinates" placeholder="Enter coordinates" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Serial Number -->
                                <div>
                                    <label for="serial_number" class="block text-sm font-medium text-gray-900">Número de Serie</label>
                                    <input type="text" id="serial_number" name="serial_number" placeholder="Enter serial number" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Installation Date -->
                                <div>
                                    <label for="installation_date" class="block text-sm font-medium text-gray-900">Fecha de Instalación</label>
                                    <input type="date" id="installation_date" name="installation_date" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Image -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-900">Imagen</label>
                                    <input type="file" id="photo" name="photo"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-800">Cancel</button>
                                    <button type="submit" class="bg-blue-800 text-white px-4 py-2 rounded-md hover:bg-blue-800">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- CSV Upload Modal -->
        <div id="csv-upload-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-10 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-y-auto h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <h2 class="text-lg font-semibold text-gray-900">Subir CSV</h2>
                        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="csv-upload-modal" aria-label="Cerrar modal">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        <form action="/uploadcsv" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="csv_file" class="block text-sm font-medium text-gray-900">Archivo CSV</label>
                                <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-800" data-modal-hide="csv-upload-modal">Cancelar</button>
                                <button type="submit" class="bg-blue-800 text-white px-4 py-2 rounded-md hover:bg-blue-800">Subir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
          <!-- Modal para editar usuario -->
    <?php foreach ($machines as $machine) : ?>
        <div id="edit-machine-modal-<?= $machine['id'] ?>" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-lg shadow">
                    <!-- Encabezado -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-900">Editar Máquina</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="edit-machine-modal-<?= $machine['id'] ?>">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Cuerpo -->
                    <div class="p-6">
                        <form action="/editmachine" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $machine['id'] ?>">
                            <div class="grid gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="edit-name-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Nombre</label>
                                    <input type="text" id="edit-name-<?= $machine['id'] ?>" name="name" 
                                        value="<?= htmlspecialchars($machine['name']) ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Apellido -->
                                <div>
                                    <label for="edit-model-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Modelo</label>
                                    <input type="text" id="edit-model-<?= $machine['id'] ?>" name="model" 
                                        value="<?= htmlspecialchars($machine['model']) ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="edit-manufacturer-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Fabricante</label>
                                    <input type="text" id="edit-manufacturer-<?= $machine['id'] ?>" name="manufacturer" 
                                        value="<?= htmlspecialchars($machine['manufacturer']) ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Contraseña -->
                                <div>
                                    <label for="edit-location-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Ubicación</label>
                                    <input type="text" id="edit-location-<?= $machine['id'] ?>" name="location" 
                                        value="<?= htmlspecialchars($machine['location']) ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Rol -->
                                <?php
                                // Ensure variables are set before use
                                $coordinates = isset($machine['coordinates']) ? htmlspecialchars($machine['coordinates']) : '';
                                $photo = isset($machine['photo']) ? htmlspecialchars($machine['photo']) : '';

                                // Use these variables in your HTML
                                ?>
                                <div>
                                    <label for="edit-coordinates-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Coordenadas</label>
                                    <input type="text" id="edit-coordinates-<?= $machine['id'] ?>" name="coordinates" 
                                        value="<?= $coordinates ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Avatar -->
                                <div>
                                    <label for="edit-photo-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Imagen</label>
                                    <input type="file" id="edit-photo-<?= $machine['id'] ?>" name="photo"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <input type="hidden" name="current_photo" value="<?= $photo ?>">
                                    <?php if (!empty($machine['photo'])) : ?>
                                        <p class="mt-2 text-sm text-gray-500">Imagen actual: <?= htmlspecialchars($machine['photo']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <!-- Botones -->
                                <div class="flex justify-end space-x-2">
                                    <button type="button" 
                                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600" 
                                        data-modal-hide="edit-machine-modal-<?= $machine['id'] ?>">
                                        Cancelar
                                    </button>
                                    <button type="submit" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


        <?php foreach ($machines as $machine): ?>
            <div class="machine-entry bg-gray-200 p-4 rounded-lg shadow-md mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold"><?php echo htmlspecialchars($machine['name']) ?></h2>
                        <p class="text-sm"> <?php echo htmlspecialchars($machine['model']) ?>, <?php echo htmlspecialchars($machine['location']) ?></p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="machinedetail/<?php echo htmlspecialchars($machine['id']); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-800 border border-transparent rounded-lg shadow-sm hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                            Ver Mas
                        </a>
                           <!-- Botón Editar -->
                    <button type="button"
                        data-modal-target="edit-machine-modal-<?= $machine['id'] ?>"
                        data-modal-toggle="edit-machine-modal-<?= $machine['id'] ?>"
                        data-machine-id="<?= $machine['id'] ?>"
                        class="p-2 text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-300"
                        aria-label="Editar">
                        <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                        </svg>

                    </button>
                        <form action="/deletemachine/<?php echo htmlspecialchars($machine['id']); ?>" method="POST" class="inline">
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Eliminar máquina">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>




</body>
<script src="js/bundle.js"></script>
<script src="js/flowbite.min.js"></script>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Inventory</title>
    <link rel="stylesheet" href="/main.css">


</head>

<body class="bg-gray-100 min-h-screen">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="max-w-7xl mx-auto p-4 sm:p-8 ">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Inventario de Máquinas</h1>
        <div class="flex mb-4 justify-end space-x-4 ">
            <button data-modal-target="machine-modal" data-modal-toggle="machine-modal"
                class="bg-blue-800 text-white hover:bg-blue-900 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden md:inline">Añadir M��quina</span>
            </button>
            <button data-modal-target="csv-upload-modal" data-modal-toggle="csv-upload-modal"
                class="bg-blue-800 text-white hover:bg-blue-900 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 3a1 1 0 0 1 .78.375l4 5a1 1 0 1 1-1.56 1.25L13 6.85V14a1 1 0 1 1-2 0V6.85L8.78 9.626a1 1 0 1 1-1.56-1.25l4-5A1 1 0 0 1 12 3ZM9 14v-1H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-4v1a3 3 0 1 1-6 0Zm8 2a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H17Z" clip-rule="evenodd" />
                </svg>
                <span class="hidden md:inline">Añadir Máquina CSV</span>
            </button>
            <button onclick="window.location.href='mapmachines'"
                class="bg-green-800 text-white hover:bg-green-900 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center"
                aria-label="Ver Mapa">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" aria-label="Ver Mapa">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                </svg>
            </button>
        </div>
        <!-- Add Machine Modal -->
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
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                    <label for="machine-photo" class="block text-sm font-medium text-gray-900">Imagen</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="file"
                                            id="machine-photo"
                                            name="photo"
                                            accept="image/*"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">

                                        </div>
   <!-- Webcam -->
   <div id="webcam-container">
            <button type="button" id="take-photo" class="mt-2 bg-blue-600 text-white px-4 py-2">Tomar Foto</button>
            <video id="video-preview" width="320" height="240" autoplay class="rounded-lg"></video>
            <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
            <img id="photo" class="mt-2 rounded-lg" alt="Captured Photo" />
        </div>
                                </div>
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



    <!-- Modal para editar maquinas -->
    <?php foreach ($machines as $machine) : ?>
        <div id="edit-machine-modal-<?= $machine['id'] ?>" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <h2 class="text-lg font-semibold text-gray-900">Editar Máquina</h2>
                        <button type="button" class="text-gray-800 hover:text-gray-900" data-modal-hide="edit-machine-modal-<?= $machine['id'] ?>" aria-label="Cerrar modal">
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
                                <!-- Modelo -->
                                <div>
                                    <label for="edit-model-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Modelo</label>
                                    <input type="text" id="edit-model-<?= $machine['id'] ?>" name="model"
                                        value="<?= htmlspecialchars($machine['model']) ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Fabricante -->
                                <div>
                                    <label for="edit-manufacturer-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Fabricante</label>
                                    <input type="text" id="edit-manufacturer-<?= $machine['id'] ?>" name="manufacturer"
                                        value="<?= htmlspecialchars($machine['manufacturer']) ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Ubicacion -->
                                <div>
                                    <label for="edit-location-<?= $machine['id'] ?>" class="block text-sm font-medium text-gray-900">Ubicación</label>
                                    <input type="text" id="edit-location-<?= $machine['id'] ?>" name="location"
                                        value="<?= htmlspecialchars($machine['location']) ?>" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Fecha de Instalación -->

                                <!-- Coordenadas y Foto -->
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
                                    <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-800" data-modal-hide="csv-upload-modal">Cancelar</button>
                                    <button type="submit"
                                        class="bg-blue-800 text-white px-4 py-2 rounded-md hover:bg-blue-900">
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

    <div class="grid grid-cols-1 gap-4">
        <?php foreach ($machines as $machine): ?>
            <div class="bg-white p-2 sm:p-4 lg:p-3 px-4 rounded-lg shadow-md flex justify-between items-center">
                <div class="flex flex-col gap-1 p-2 machine-card">
                    <h2 class="text-base sm:text-lg font-bold"><?php echo htmlspecialchars($machine['name']) ?></h2>
                    <p class="text-xs sm:text-sm"><?php echo htmlspecialchars($machine['location']) ?>, <?php echo htmlspecialchars($machine['model']) ?></p>
                </div>
                <div class="relative">
                    <button class="p-1 sm:p-2 text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-300" aria-label="Opciones" data-dropdown-toggle="dropdown-<?= $machine['id'] ?>">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                    <div id="dropdown-<?= $machine['id'] ?>" class="hidden absolute right-0 mt-2 w-40 sm:w-48 bg-gray-100 rounded-md shadow-lg z-10">
                        <button>
                            <a href="machinedetail/<?php echo htmlspecialchars($machine['id']); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ver Más</a>
                        </button>
                        <button type="button" data-modal-target="edit-machine-modal-<?php echo $machine['id'] ?>" data-modal-toggle="edit-machine-modal-<?php echo $machine['id'] ?>" class="block w-full text-left px-4 py-2 text-sm text-blue-800 hover:bg-blue-50">
                            Editar
                        </button>
                        <button type="button" onclick="showQRCode('<?php echo htmlspecialchars($machine['id']); ?>')" class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                Generar QR
                            </button>

                        <form action="/deletemachine/<?php echo htmlspecialchars($machine['id']); ?>" method="POST" class="block">
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
    <script src="/js/bundle.js"></script>
    <script src="/js/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
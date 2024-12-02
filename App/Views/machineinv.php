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
  <?php include __DIR__ . "/layouts/navbar.php"; ?>

  <div class="max-w-7xl mx-auto p-8 mt-20 rounded-lg">
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Inventario de Máquinas</h1>
    <div class="flex justify-end mb-4">
         <!-- Button to open modal -->
         <div class="flex justify-end mt-4">
            <button data-modal-target="machine-modal" data-modal-toggle="machine-modal"
                class="bg-gray-800 text-white hover:bg-gray-700 focus:ring-4 focus:ring-gray-600 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Añadir Máquina
            </button>
        </div>

        <!-- Modal -->
        <div id="machine-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-900">Add Machine</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="machine-modal">
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
                                    <label for="name" class="block text-sm font-medium text-gray-900">Name</label>
                                    <input type="text" id="name" name="name" placeholder="Enter name" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Model -->
                                <div>
                                    <label for="model" class="block text-sm font-medium text-gray-900">Model</label>
                                    <input type="text" id="model" name="model" placeholder="Enter model" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Manufacturer -->
                                <div>
                                    <label for="manufacturer" class="block text-sm font-medium text-gray-900">Manufacturer</label>
                                    <input type="text" id="manufacturer" name="manufacturer" placeholder="Enter manufacturer" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Location -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-900">Location</label>
                                    <input type="text" id="location" name="location" placeholder="Enter location" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Serial Number -->
                                <div>
                                    <label for="serial_number" class="block text-sm font-medium text-gray-900">Serial Number</label>
                                    <input type="text" id="serial_number" name="serial_number" placeholder="Enter serial number" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Installation Date -->
                                <div>
                                    <label for="installation_date" class="block text-sm font-medium text-gray-900">Installation Date</label>
                                    <input type="date" id="installation_date" name="installation_date" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Image -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-900">Image</label>
                                    <input type="file" id="photo" name="photo"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
<script src="js/bundle.js"></script>
<script src="js/flowbite.min.js"></script>

</html>
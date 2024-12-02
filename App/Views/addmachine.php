<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Añadir Máquina</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="css/main.css">
</head>

<body class="bg-gray-100 min-h-screen">
  <?php include __DIR__ . "/layouts/navbar.php"; ?>

  <div class="flex items-center justify-center pt-20">
    <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Añadir Máquina</h1>
      <form action="addmachine" method="POST" class="space-y-6" enctype="multipart/form-data">
        <!-- Nombre y Modelo -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="name" name="name" required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label for="model" class="block text-sm font-medium text-gray-700">Modelo</label>
            <input type="text" id="model" name="model" required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>

        <!-- Fabricante -->
        <div>
          <label for="manufacturer" class="block text-sm font-medium text-gray-700">Fabricante</label>
          <input type="text" id="manufacturer" name="manufacturer" required
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Imagen -->
        <div>
          <label for="photo" class="block text-sm font-medium text-gray-700">Imagen</label>
          <input type="file" id="photo" name="photo"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Ubicación -->
        <div>
          <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
          <input type="text" id="location" name="location" required
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Número de Serie y Fecha de Instalación -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="serial_number" class="block text-sm font-medium text-gray-700">Número de Serie</label>
            <input type="text" id="serial_number" name="serial_number" required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label for="installation_date" class="block text-sm font-medium text-gray-700">Fecha de Instalación</label>
            <input type="date" id="installation_date" name="installation_date" required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>

        <!-- Botón de envío -->
        <div>
          <button type="submit"
          class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-800 border border-transparent rounded-lg shadow-sm hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
            Agregar Máquina
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>


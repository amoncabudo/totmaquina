<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de la Máquina</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<?php include __DIR__ . "/layouts/navbar.php"; ?>

<body class="bg-gray-100 min-h-screen">
  <div class="max-w-3xl mx-auto p-8 mt-10 rounded-lg">
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Detalle de la Máquina</h1>
    <div class="bg-gray-200 p-4 rounded-lg shadow-md grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h2 class="text-lg font-bold mb-6"><?php echo htmlspecialchars($machine['name']) ?></h2>
        <p class="mb-6"><strong>Modelo:</strong> <?php echo htmlspecialchars($machine['model']) ?></p>
        <p class="mb-6"><strong>Fabricante:</strong> <?php echo htmlspecialchars($machine['manufacturer']) ?></p>
        <p class="mb-6"><strong>Fecha de instalación:</strong> <?php echo htmlspecialchars($machine['installation_date']) ?></p>
        <p class="mb-6"><strong>Número de serie:</strong> <?php echo htmlspecialchars($machine['serial_number']) ?></p>
        <p class="mb-6"><strong>Ubicación:</strong> <?php echo htmlspecialchars($machine['location']) ?></p>
        <p class="mb-6"><strong>Coordenadas:</strong> <?php echo htmlspecialchars($machine['coordinates']) ?></p>
      </div>
      <div class="flex flex-col items-center">
        <img src="<?php echo htmlspecialchars('/Images/' . $machine['photo']); ?>" alt="Imagen de la máquina" class="w-full mb-4 ">
       
      </div>
    </div>
    
    <div class="flex justify-end mt-4">
      <a href="javascript:void(0);" onclick="history.back();" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9h13a5 5 0 0 1 0 10H7M3 9l4-4M3 9l4 4"/>
        </svg>  
        <span class="sr-only">Volver</span>
      </a>
    </div>
  </div>
</body>
<script src="/js/bundle.js"></script>
<script src="/js/flowbite.min.js"></script>
</html> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="bg-gray-100">

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
  <div class="bg-gray-200 p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-lg font-bold">Nombre de la maquina</h2>
        <p class="text-sm">Modelo Fabricante Coordenadas Ubicación</p>
      </div>
      <div class="relative">
        <button id="dropdownButton1" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 flex items-center">
          
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div id="dropdownContent1" class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
         
        </div>
      </div>
    </div>
    <div id="rectangle1" class="bg-gray-300 p-4 mt-4 rounded-lg shadow-md hidden">
      <div class="flex justify-between">
        <div>
          <p class="font-bold">Nombre de la maquina</p>
          <p>Modelo</p>
          <p>Fecha de instalación</p>
          <p>Ubicación</p>
          <p>Número de serie</p>
          <p>Asignar a técnico</p>
        </div>
        <div>
          <p class="font-bold">Fabricante</p>
          <p>Coordenadas</p>
        </div>
        <div class="flex items-center">
          <img src="path/to/image" alt="Machine Image" class="w-16 h-16">
          <button class="ml-2 text-sm">generar qr</button>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-gray-200 p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-lg font-bold">Nombre de la maquina</h2>
        <p class="text-sm">Modelo Fabricante Coordenadas Ubicación</p>
      </div>
      <div class="relative">
        <button id="dropdownButton2" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 flex items-center">
          Seleccionar
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div id="dropdownContent2" class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
          <ul class="p-2.5">
            <li>Nombre de la maquina</li>
            <li>Modelo</li>
            <li>Fabricante</li>
            <li>Coordenadas</li>
            <li>Ubicación</li>
          </ul>
        </div>
      </div>
    </div>
    <div id="rectangle2" class="bg-gray-300 p-4 mt-4 rounded-lg shadow-md hidden">
      <div class="flex justify-between">
        <div>
          <p class="font-bold">Nombre de la maquina</p>
          <p>Modelo</p>
          <p>Fecha de instalación</p>
          <p>Ubicación</p>
          <p>Número de serie</p>
          <p>Asignar a técnico</p>
        </div>
        <div>
          <p class="font-bold">Fabricante</p>
          <p>Coordenadas</p>
        </div>
        <div class="flex items-center">
          <img src="path/to/image" alt="Machine Image" class="w-16 h-16">
          <button class="ml-2 text-sm">generar qr</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleDropdown(buttonId) {
    // Hide all dropdowns and rectangles
    document.querySelectorAll('.dropdown-content').forEach(dropdown => {
      dropdown.classList.add('hidden');
    });
    document.querySelectorAll('.rectangle').forEach(rectangle => {
      rectangle.classList.add('hidden');
    });

    // Get the current dropdown and rectangle
    const currentDropdown = document.getElementById('dropdownContent' + buttonId);
    const currentRectangle = document.getElementById('rectangle' + buttonId);

    // Toggle the current dropdown and rectangle
    currentDropdown.classList.toggle('hidden');
    currentRectangle.classList.toggle('hidden');
  }

  document.getElementById('dropdownButton1').addEventListener('click', function() {
    toggleDropdown('1');
  });

  document.getElementById('dropdownButton2').addEventListener('click', function() {
    toggleDropdown('2');
  });
</script>

</body>
</html>
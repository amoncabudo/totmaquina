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
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
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
        <button id="dropdownButton1" data-dropdown-toggle="dropdownContent1" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 flex items-center">
          <span>Opciones</span>
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div id="dropdownContent1" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700">
          <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownButton1">
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Opción 1</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Opción 2</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Opción 3</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div id="additionalInfo" class="hidden bg-gray-300 p-4 rounded-lg shadow-md mt-2">
    <p>Información adicional sobre la máquina:</p>
    <ul>
      <li>Fecha de instalación: 01/01/2023</li>
      <li>Número de serie: 123456789</li>
      <li>Asignar a técnico: Juan Pérez</li>
    </ul>
  </div>
  <div class="bg-gray-200 p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-lg font-bold">Nombre de la maquina</h2>
        <p class="text-sm">Modelo Fabricante Coordenadas Ubicación</p>
      </div>
      <div class="relative">
        <button id="dropdownButton1" data-dropdown-toggle="dropdownContent1" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 flex items-center">
          <span>Opciones</span>
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div id="dropdownContent1" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700">
        <div id="additionalInfo" class="hidden bg-gray-300 p-4 rounded-lg shadow-md mt-2">
    <p>Información adicional sobre la máquina:</p>
    <ul>
      <li>Fecha de instalación: 01/01/2023</li>
      <li>Número de serie: 123456789</li>
      <li>Asignar a técnico: Juan Pérez</li>
    </ul>
  </div>
        </div>
      </div>
    </div>
  </div>
 
</div>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="/js/bundle.js"></script>
<script>
  document.querySelector('#dropdownButton1').addEventListener('click', function() {
    const dropdown = document.getElementById('dropdownContent1');
    const additionalInfo = document.getElementById('additionalInfo');
    dropdown.classList.toggle('hidden');
    additionalInfo.classList.toggle('hidden');
  });
</script>
</body>
</html>
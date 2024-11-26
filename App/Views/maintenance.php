<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Mantenimiento</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="bg-gray-100 min-h-screen">


  <div class="max-w-3xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Historial de Mantenimiento</h1>
    <form id="maintenance-form">
      <label for="machine-select" class="block text-lg font-medium text-gray-700 mb-2">Selecciona una máquina:</label>
      <select id="machine-select" name="machine" class="w-full border border-gray-300 rounded-lg p-2 mb-4">
        <option value="">-- Selecciona una máquina --</option>
        <option value="maquina1">Máquina 1</option>
        <option value="maquina2">Máquina 2</option>
        <option value="maquina3">Máquina 3</option>
      </select>
      
      <div id="machine-info" class="bg-gray-50 border border-gray-300 rounded-lg p-4 mb-6 hidden">
        <p class="text-gray-700"><strong>Nombre:</strong> <span id="info-nombre"></span></p>
        <p class="text-gray-700"><strong>Modelo:</strong> <span id="info-modelo"></span></p>
        <p class="text-gray-700"><strong>Fabricante:</strong> <span id="info-fabricante"></span></p>
        <p class="text-gray-700"><strong>Coordenadas:</strong> <span id="info-coordenadas"></span></p>
        <p class="text-gray-700"><strong>Ubicación:</strong> <span id="info-ubicacion"></span></p>
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
        Consultar Historial
      </button>
    </form>
  </div>

  <script >const machines = {
    maquina1: {
      nombre: "Máquina 1",
      modelo: "Modelo A",
      fabricante: "Fabricante X",
      coordenadas: "41.3851, 2.1734",
      ubicacion: "Nave 1"
    },
    maquina2: {
      nombre: "Máquina 2",
      modelo: "Modelo B",
      fabricante: "Fabricante Y",
      coordenadas: "40.4168, -3.7038",
      ubicacion: "Nave 2"
    },
    maquina3: {
      nombre: "Máquina 3",
      modelo: "Modelo C",
      fabricante: "Fabricante Z",
      coordenadas: "39.4699, -0.3763",
      ubicacion: "Nave 3"
    }
  };

  // DOM references
  const selectMachine = document.getElementById("machine-select");
  const infoBox = document.getElementById("machine-info");
  const infoNombre = document.getElementById("info-nombre");
  const infoModelo = document.getElementById("info-modelo");
  const infoFabricante = document.getElementById("info-fabricante");
  const infoCoordenadas = document.getElementById("info-coordenadas");
  const infoUbicacion = document.getElementById("info-ubicacion");

  // Update the displayed information based on the selected machine
  selectMachine.addEventListener("change", function () {
    const selectedValue = selectMachine.value;

    if (selectedValue && machines[selectedValue]) {
      const machine = machines[selectedValue];
      infoNombre.textContent = machine.nombre;
      infoModelo.textContent = machine.modelo;
      infoFabricante.textContent = machine.fabricante;
      infoCoordenadas.textContent = machine.coordenadas;
      infoUbicacion.textContent = machine.ubicacion;

      infoBox.classList.remove("hidden"); // Show the information box
    } else {
      infoBox.classList.add("hidden"); // Hide the information box if no machine is selected
    }
  });

  // Handle redirection
  const form = document.getElementById("maintenance-form");
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    const selectedMachine = selectMachine.value;
    if (selectedMachine) {
      window.location.href = `history.php?maquina=${selectedMachine}`;
    } else {
      alert("Por favor, selecciona una máquina antes de continuar.");
    }
  });</script>
  
</body>
</html>

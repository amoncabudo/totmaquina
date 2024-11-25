<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Mantenimiento</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .container {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    h1 {
      text-align: center;
      color: #333;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 10px;
      color: #555;
    }
    select {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .info-box {
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      display: none; /* Hidden by default */
    }
    .info-box p {
      margin: 5px 0;
    }
    button {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      color: white;
      background-color: #007BFF;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
  <div class="container">
    <h1>Historial de Mantenimiento</h1>
    <form id="maintenance-form">
      <label for="machine-select">Selecciona una máquina:</label>
      <select id="machine-select" name="machine">
        <option value="">-- Selecciona una máquina --</option>
        <option value="maquina1">Máquina 1</option>
        <option value="maquina2">Máquina 2</option>
        <option value="maquina3">Máquina 3</option>
      </select>
      <div class="info-box" id="machine-info">
        <p><strong>Nombre:</strong> <span id="info-nombre"></span></p>
        <p><strong>Modelo:</strong> <span id="info-modelo"></span></p>
        <p><strong>Fabricante:</strong> <span id="info-fabricante"></span></p>
        <p><strong>Coordenadas:</strong> <span id="info-coordenadas"></span></p>
        <p><strong>Ubicación:</strong> <span id="info-ubicacion"></span></p>
      </div>
      <button type="submit">Consultar Historial</button>
    </form>
  </div>

  <script>
    // Information about each machine
    const machines = {
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

        infoBox.style.display = "block"; // Show the information box
      } else {
        infoBox.style.display = "none"; // Hide the information box if no machine is selected
      }
    });

    // Handle redirection
    const form = document.getElementById("maintenance-form");
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      const selectedMachine = selectMachine.value;
      if (selectedMachine) {
        window.location.href = `historial.html?maquina=${selectedMachine}`;
      } else {
        alert("Por favor, selecciona una máquina antes de continuar.");
      }
    });
  </script>
      <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

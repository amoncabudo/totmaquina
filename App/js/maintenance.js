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
      window.location.href = `historial.html?maquina=${selectedMachine}`;
    } else {
      alert("Por favor, selecciona una máquina antes de continuar.");
    }
  });
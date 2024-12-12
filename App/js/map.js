if (document.getElementById("map")) {
  // Inicializar el mapa con un estilo más moderno
  var map = L.map("map", {
    zoomControl: false, // Desactivamos el control de zoom predeterminado
  }).setView([42.27351400039436, 2.9648054015140053 ], 15);

  // Añadir un estilo de mapa más moderno
  L.tileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
    attribution: "© OpenStreetMap contributors",
    maxZoom: 19,
  }).addTo(map);

  // Añadir control de zoom en una posición personalizada
  L.control
    .zoom({
      position: "bottomright",
    })
    .addTo(map);
  // Definir el icono personalizado
  const customIcon = L.divIcon({
    html: '<i class="fa-solid fa-location-dot fa-2x" style="color: #4169e1;"></i>',
    iconSize: [30, 30],
    className: "custom-div-icon",
    iconAnchor: [15, 30],
    popupAnchor: [0, -30],
  });

  // Función para cargar los marcadores
  window.loadMarkers = function (machines) {
    if (!machines || machines.length === 0) {
      console.log("No hay máquinas para mostrar");
      return;
    }
    console.log(machines);
    machines.forEach(function (machine) {
      // Verificar que machine.coordinates existe y no es undefined/null
      if (machine.coordinates && typeof machine.coordinates === "string") {
        const coords = machine.coordinates.split(",");

        // Verificar que tenemos dos valores después de dividir
        if (coords.length === 2) {
          const lat = parseFloat(coords[0].trim());
          const lng = parseFloat(coords[1].trim());

          // Verificar que los valores son números válidos
          if (!isNaN(lat) && !isNaN(lng)) {
            const marker = L.marker([lat, lng], {
              icon: customIcon,
            }).addTo(map);
            // Añadir popup al marcador
            console.log("Machine data:", machine); // Para debug

            marker
              .bindPopup(
                `
                      <div class="popup-content">
                          <h3 class="font-bold">${machine.name || "Máquina"}</h3>
                          <p>${machine.location || "Sin Ubicación"}</p>
                          <a href="http://localhost/machinedetail/${machine.id}">Detalles de la maquina </a>
                          
                      </div>
                  `
              )
              .openPopup();
          } else {
            console.error("Coordenadas inválidas para la máquina:", machine);
          }
        } else {
          console.error(
            "Formato de coordenadas incorrecto para la máquina:",
            machine
          );
        }
      } else {
        console.error("Coordenadas no definidas para la máquina:", machine);
      }
    });
  };
}
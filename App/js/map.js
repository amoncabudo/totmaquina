if (document.getElementById("map")) { //If the map element exists, run the code
  var map = L.map("map", { //Create a new map with the id map
    zoomControl: false, //Disable the default zoom control
  }).setView([42.2735257096842, 2.9648616030527037], 15); //Set the view to the coordinates [42.2735257096842, 2.9648616030527037] and the zoom level to 15

  L.tileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", { //Add a tile layer with the url https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png
    attribution: "© OpenStreetMap contributors", //Set the attribution to © OpenStreetMap contributors
    maxZoom: 19, //Set the max zoom level to 19
  }).addTo(map); //Add the tile layer to the map

  L.control //Add a control to the map
    .zoom({ //Add a zoom control to the map
      position: "bottomright", //Set the position to bottomright
    })
    .addTo(map);

  const customIcon = L.divIcon({ //Add a custom icon to the map
    html: '<i class="fa-solid fa-location-dot fa-2x" style="color: #4169e1;"></i>', //Set the html to <i class="fa-solid fa-location-dot fa-2x" style="color: #4169e1;"></i>
    iconSize: [30, 30], //Set the icon size to 30x30
    className: "custom-div-icon", //Set the class name to custom-div-icon
    iconAnchor: [15, 30], //Set the icon anchor to 15x30
    popupAnchor: [0, -30], //Set the popup anchor to 0x-30
  });

  window.loadMarkers = function (machines) { //When the function loadMarkers is called, run the code
    if (!machines || machines.length === 0) { //If the machines array is null or empty, run the code
      console.log("No hay máquinas para mostrar"); 
      return; 
    }
    console.log(machines); //Show the machines array
    machines.forEach(function (machine) { //For each machine in the machines array
      if (machine.coordinates && typeof machine.coordinates === "string") { //If the machine.coordinates exists and is a string, run the code
        const coords = machine.coordinates.split(","); //Split the machine.coordinates string by commas

        if (coords.length === 2) { //If the coords array has 2 values, run the code
          const lat = parseFloat(coords[0].trim()); //Parse the first value of the coords array as a float
          const lng = parseFloat(coords[1].trim()); //Parse the second value of the coords array as a float

          if (!isNaN(lat) && !isNaN(lng)) { //If the lat and lng values are not NaN, run the code
            const marker = L.marker([lat, lng], { //Create a new marker with the coordinates [lat, lng]
              icon: customIcon, //Set the icon to the custom icon
            }).addTo(map); //Add the marker to the map
            console.log("Machine data:", machine); //Show the machine data

            marker //Bind the popup to the marker
              .bindPopup(
                `
                      <div class="popup-content">
                          <h3 class="font-bold">${machine.name || "Máquina"}</h3>
                          <p>${machine.location || "Sin Ubicación"}</p>
                          <a href="http://grup7dawcendrassos.cat/machinedetail/${machine.id}">Detalles de la maquina </a>
                          
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

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el mapa con un nivel de zoom más bajo
    var map = L.map('map').setView([41.5, 2.5], 8); // Coordenadas centradas entre Figueres y Barcelona

    // Añadir capa de mapa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Añadir un marcador en Figueres
    L.marker([42.27353135417709, 2.964941545247782]).addTo(map)
        .bindPopup('Servidor Secundario, Figueres C/ Pelai Martinez 1')
        .openPopup();

    // Añadir un marcador en Barcelona
    L.marker([41.387589, 2.171852]).addTo(map)
        .bindPopup('Servidor Principal, Barcelona C/ Fontanella 23')
        .openPopup();

        var popup = L.popup();

        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent("Hiciste click en el mapa en " + e.latlng.toString())
                .openOn(map);
        }
        
        map.on('click', onMapClick);
});
describe('showQRCode function', () => {
    let openMock;

    beforeEach(() => {
        // Mock de fetch
        global.fetch = jest.fn(() =>
            Promise.resolve({
                text: () => Promise.resolve('<svg>QR Code</svg>'),
            })
        );

        // Mock de window.open para simular la apertura de una ventana
        openMock = jest.fn().mockReturnValue({ document: { write: jest.fn() } });
        global.window.open = openMock;
    });

    // Test para showQRCode
    test('should open a new window and write QR code data', async () => {
        // Simulamos que el documento está disponible
        document.body.innerHTML = `<div id="video"></div><canvas id="canvas"></canvas><button id="capture-photo"></button>`;

        // Llamamos a la función con un ID de máquina ficticio
        await showQRCode(123);

        // Verificamos que fetch haya sido llamado con la URL correcta
        expect(fetch).toHaveBeenCalledWith('/generate_qr.php?id=123');
        
        // Verificamos que se haya llamado window.open
        expect(openMock).toHaveBeenCalled();
        
        // Verificamos que document.write haya sido llamado con los datos del QR
        expect(openMock().document.write).toHaveBeenCalledWith('<svg>QR Code</svg>');
    });
});
// map.test.js

// Mocking Leaflet methods to test map functionality
jest.mock('leaflet', () => ({
    map: jest.fn().mockReturnValue({
      setView: jest.fn(),
      on: jest.fn(),
      addLayer: jest.fn(),
    }),
    tileLayer: jest.fn().mockReturnValue({
      addTo: jest.fn(),
    }),
    marker: jest.fn().mockReturnValue({
      addTo: jest.fn(),
      bindPopup: jest.fn(),
      openPopup: jest.fn(),
    }),
    popup: jest.fn().mockReturnValue({
      setLatLng: jest.fn(),
      setContent: jest.fn(),
      openOn: jest.fn(),
    }),
  }));
  
  describe('Map Initialization', () => {
    let mapScript;
  
    beforeAll(() => {
      // Creamos un div de mapa para simular el DOM
      document.body.innerHTML = '<div id="map"></div>';
  
      // Importamos el script después de simular el DOM
      mapScript = require('../src/map.js');
    });
  
    test('should initialize the map with correct zoom and coordinates', () => {
      // Verificar si la función map fue llamada correctamente con el id 'map' y las coordenadas iniciales
      expect(L.map).toHaveBeenCalledWith('map');
      expect(L.map().setView).toHaveBeenCalledWith([41.5, 2.5], 8); // Coordenadas centradas y zoom nivel 8
    });
  
    test('should add a base layer to the map', () => {
      expect(L.tileLayer).toHaveBeenCalledWith(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        expect.objectContaining({
          maxZoom: 19,
          attribution: expect.stringContaining('OpenStreetMap')
        })
      );
      expect(L.tileLayer().addTo).toHaveBeenCalledWith(L.map());
    });
  
    test('should add markers to Figueres and Barcelona', () => {
      expect(L.marker).toHaveBeenCalledWith([42.27353135417709, 2.964941545247782]);
      expect(L.marker().bindPopup).toHaveBeenCalledWith('Servidor Secundario, Figueres C/ Pelai Martinez 1');
      expect(L.marker().openPopup).toHaveBeenCalled();
  
      expect(L.marker).toHaveBeenCalledWith([41.387589, 2.171852]);
      expect(L.marker().bindPopup).toHaveBeenCalledWith('Servidor Principal, Barcelona C/ Fontanella 23');
      expect(L.marker().openPopup).toHaveBeenCalled();
    });
  
    test('should handle map click events and display popup', () => {
      // Simulamos el evento de click en el mapa
      const mockEvent = { latlng: { lat: 42, lng: 2 } };
      const onMapClick = L.map().on.mock.calls[0][1]; // Extraemos la función que se pasó al evento 'click'
      
      // Ejecutamos la función asociada al evento click
      onMapClick(mockEvent);
      
      // Verificamos que el popup haya sido creado correctamente
      expect(L.popup().setLatLng).toHaveBeenCalledWith(mockEvent.latlng);
      expect(L.popup().setContent).toHaveBeenCalledWith('Hiciste click en el mapa en LatLng(42, 2)');
      expect(L.popup().openOn).toHaveBeenCalledWith(L.map());
    });
  });
  
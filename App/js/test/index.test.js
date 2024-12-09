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

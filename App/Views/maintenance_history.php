<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tot Màquina - Historial de Mantenimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="/main.css">
</head>
<body class="bg-gray-100">

<?php include __DIR__ . "/layouts/navbar.php"; ?>

<main class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">Historial de Mantenimiento</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form id="maintenance-form" class="mb-8">
                <div class="mb-4">
                    <label for="machine-select" class="block text-lg font-medium text-gray-700 mb-2">Selecciona una máquina:</label>
                    <select id="machine-select" name="machine" class="w-full border border-gray-300 rounded-lg p-2">
                        <option value="">-- Selecciona una máquina --</option>
                        <?php foreach ($machines as $machine): ?>
                            <option value="<?= htmlspecialchars($machine['id']) ?>">
                                <?= htmlspecialchars($machine['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Consultar Historial
                </button>
            </form>

            <div id="machine-info" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Información de la Máquina</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p class="text-gray-700"><strong>Nombre:</strong> <span id="info-nombre"></span></p>
                    <p class="text-gray-700"><strong>Modelo:</strong> <span id="info-modelo"></span></p>
                    <p class="text-gray-700"><strong>Fabricante:</strong> <span id="info-fabricante"></span></p>
                    <p class="text-gray-700"><strong>Ubicación:</strong> <span id="info-ubicacion"></span></p>
                </div>
            </div>

            <div id="maintenance-history" class="hidden">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Registros de Mantenimiento</h2>
                <div id="history-content" class="space-y-4">
                    <!-- Los registros se insertarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('maintenance-form');
    const machineSelect = document.getElementById('machine-select');
    const historyContent = document.getElementById('history-content');
    const maintenanceHistory = document.getElementById('maintenance-history');
    const machineInfo = document.getElementById('machine-info');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const machineId = machineSelect.value;
        
        if (!machineId) {
            alert('Por favor, selecciona una máquina');
            return;
        }

        try {
            const response = await fetch(`/api/maintenance/history/${machineId}`);
            if (!response.ok) throw new Error('Error al obtener el historial');
            
            const history = await response.json();
            console.log('Historial recibido:', history);
            
            historyContent.innerHTML = '';
            
            if (history && history.length > 0) {
                history.forEach(record => {
                    const recordElement = document.createElement('div');
                    recordElement.className = 'bg-white p-6 rounded-lg shadow mb-4 border-l-4 border-blue-500';
                    
                    // Determinar el color del estado
                    let statusColor;
                    switch(record.status.toLowerCase()) {
                        case 'completado':
                            statusColor = 'bg-green-100 text-green-800';
                            break;
                        case 'en proceso':
                            statusColor = 'bg-blue-100 text-blue-800';
                            break;
                        default:
                            statusColor = 'bg-yellow-100 text-yellow-800';
                    }
                    
                    recordElement.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div class="flex-grow">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        ${new Date(record.date).toLocaleDateString('es-ES', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        })}
                                    </h3>
                                    <span class="px-3 py-1 rounded-full text-sm ${statusColor}">
                                        ${record.status}
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-2"><strong>Tipo:</strong> ${record.type}</p>
                                <p class="text-gray-600 mb-2"><strong>Técnico(s):</strong> ${record.technician}</p>
                                <p class="text-gray-600 mt-2">${record.description}</p>
                            </div>
                        </div>
                    `;
                    historyContent.appendChild(recordElement);
                });
                maintenanceHistory.classList.remove('hidden');
            } else {
                historyContent.innerHTML = `
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-gray-600">No hay registros de mantenimiento para esta máquina.</p>
                    </div>
                `;
                maintenanceHistory.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al obtener el historial de mantenimiento');
        }
    });
});
</script>
</body>
</html>
<?php 
$viewsPath = __DIR__;
include $viewsPath . "/layouts/navbar.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">
</head>
<body>
    
</body>
</html>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Mis Máquinas Asignadas</h1>

    <?php if (empty($machinesData)): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No tienes máquinas asignadas actualmente.
                    </p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($machinesData as $data): ?>
            <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                <!-- Información de la máquina -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($data['machine']['name']) ?></h2>
                            <p class="text-gray-600">Modelo: <?= htmlspecialchars($data['machine']['model']) ?></p>
                            <p class="text-gray-600">Ubicación: <?= htmlspecialchars($data['machine']['location']) ?></p>
                        </div>
                        <a href="/machinedetail/<?= $data['machine']['id'] ?>" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Ver Detalles
                        </a>
                    </div>
                </div>

                <!-- Pestañas -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="showTab(this, 'incidents-<?= $data['machine']['id'] ?>')" 
                                class="tab-button active text-blue-600 border-b-2 border-blue-600 py-4 px-6 font-medium">
                            Mis Incidencias
                        </button>
                        <button onclick="showTab(this, 'my-maintenance-<?= $data['machine']['id'] ?>')" 
                                class="tab-button text-gray-500 hover:text-gray-700 py-4 px-6 font-medium">
                            Mis Mantenimientos
                        </button>
                        <button onclick="showTab(this, 'maintenance-<?= $data['machine']['id'] ?>')" 
                                class="tab-button text-gray-500 hover:text-gray-700 py-4 px-6 font-medium">
                            Historial de Mantenimiento
                        </button>
                        <button onclick="showTab(this, 'all-incidents-<?= $data['machine']['id'] ?>')" 
                                class="tab-button text-gray-500 hover:text-gray-700 py-4 px-6 font-medium">
                            Todas las Incidencias
                        </button>
                    </nav>
                </div>

                <!-- Contenido de las pestañas -->
                <div class="p-6">
                    <!-- Mis Incidencias -->
                    <div id="incidents-<?= $data['machine']['id'] ?>" class="tab-content">
                        <?php if (empty($data['assigned_incidents'])): ?>
                            <p class="text-gray-600 text-center py-4">No tienes incidencias asignadas para esta máquina.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($data['assigned_incidents'] as $incident): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-normal"><?= htmlspecialchars($incident['description']) ?></td>
                                                <td class="px-6 py-4">
                                                    <select class="status-selector incident-status px-2 py-1 rounded-full text-xs font-semibold"
                                                            data-incident-id="<?= $incident['id'] ?>"
                                                            data-current-status="<?= $incident['status'] ?>">
                                                        <option value="pending" <?= $incident['status'] === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                                                        <option value="in_progress" <?= $incident['status'] === 'in_progress' ? 'selected' : '' ?>>En Proceso</option>
                                                        <option value="resolved" <?= $incident['status'] === 'resolved' ? 'selected' : '' ?>>Resuelto</option>
                                                    </select>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($incident['registered_date']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Mis Mantenimientos -->
                    <div id="my-maintenance-<?= $data['machine']['id'] ?>" class="tab-content hidden">
                        <?php if (empty($data['assigned_maintenance'])): ?>
                            <p class="text-gray-600 text-center py-4">No tienes mantenimientos asignados para esta máquina.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frecuencia</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Programada</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($data['assigned_maintenance'] as $maintenance): ?>
                                            <tr>
                                                <td class="px-6 py-4"><?= htmlspecialchars($maintenance['type']) ?></td>
                                                <td class="px-6 py-4 whitespace-normal"><?= htmlspecialchars($maintenance['description']) ?></td>
                                                <td class="px-6 py-4"><?= htmlspecialchars($maintenance['frequency']) ?></td>
                                                <td class="px-6 py-4">
                                                    <select class="status-selector maintenance-status px-2 py-1 rounded-full text-xs font-semibold"
                                                            data-maintenance-id="<?= $maintenance['id'] ?>"
                                                            data-current-status="<?= $maintenance['status'] ?>">
                                                        <option value="pending" <?= $maintenance['status'] === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                                                        <option value="in_progress" <?= $maintenance['status'] === 'in_progress' ? 'selected' : '' ?>>En Proceso</option>
                                                        <option value="completed" <?= $maintenance['status'] === 'completed' ? 'selected' : '' ?>>Completado</option>
                                                    </select>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($maintenance['scheduled_date']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Historial de Mantenimiento -->
                    <div id="maintenance-<?= $data['machine']['id'] ?>" class="tab-content hidden">
                        <?php if (empty($data['maintenance_history'])): ?>
                            <p class="text-gray-600 text-center py-4">No hay registros de mantenimiento para esta máquina.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($data['maintenance_history'] as $maintenance): ?>
                                            <tr>
                                                <td class="px-6 py-4"><?= htmlspecialchars($maintenance['type']) ?></td>
                                                <td class="px-6 py-4 whitespace-normal"><?= htmlspecialchars($maintenance['description']) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($maintenance['scheduled_date']) ?></td>
                                                <td class="px-6 py-4"><?= htmlspecialchars($maintenance['technician_name']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Todas las Incidencias -->
                    <div id="all-incidents-<?= $data['machine']['id'] ?>" class="tab-content hidden">
                        <?php if (empty($data['all_incidents'])): ?>
                            <p class="text-gray-600 text-center py-4">No hay incidencias registradas para esta máquina.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($data['all_incidents'] as $incident): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-normal"><?= htmlspecialchars($incident['description']) ?></td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        <?= $incident['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                            ($incident['status'] === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                            'bg-green-100 text-green-800') ?>">
                                                        <?= htmlspecialchars($incident['status']) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4"><?= htmlspecialchars($incident['technician_name'] ?? 'Sin asignar') ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($incident['registered_date']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function showTab(button, contentId) {
    // Obtener el contenedor padre de las pestañas
    const tabContainer = button.closest('.bg-white');
    
    // Desactivar todas las pestañas en este contenedor
    tabContainer.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        tab.classList.add('text-gray-500');
    });
    
    // Activar la pestaña seleccionada
    button.classList.remove('text-gray-500');
    button.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    
    // Ocultar todos los contenidos en este contenedor
    tabContainer.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Mostrar el contenido seleccionado
    document.getElementById(contentId).classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambios en el estado de incidencias
    document.querySelectorAll('.incident-status').forEach(select => {
        select.addEventListener('change', function() {
            const incidentId = this.dataset.incidentId;
            const newStatus = this.value;
            const currentStatus = this.dataset.currentStatus;

            if (newStatus === currentStatus) return;

            if (confirm('¿Estás seguro de que deseas cambiar el estado de esta incidencia?')) {
                updateIncidentStatus(incidentId, newStatus, this);
            } else {
                this.value = currentStatus;
            }
        });
    });

    // Manejar cambios en el estado de mantenimientos
    document.querySelectorAll('.maintenance-status').forEach(select => {
        select.addEventListener('change', function() {
            const maintenanceId = this.dataset.maintenanceId;
            const newStatus = this.value;
            const currentStatus = this.dataset.currentStatus;

            if (newStatus === currentStatus) return;

            if (confirm('¿Estás seguro de que deseas cambiar el estado de este mantenimiento?')) {
                updateMaintenanceStatus(maintenanceId, newStatus, this);
            } else {
                this.value = currentStatus;
            }
        });
    });

    // Función para actualizar el estado de una incidencia
    function updateIncidentStatus(incidentId, status, selectElement) {
        fetch('/user-machines/update-incident-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `incident_id=${incidentId}&status=${status}`
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Error al actualizar el estado');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                selectElement.dataset.currentStatus = status;
                updateStatusStyle(selectElement, status);
                showNotification('Estado actualizado correctamente', 'success');
            } else {
                throw new Error(data.message || 'Error al actualizar el estado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            selectElement.value = selectElement.dataset.currentStatus;
            showNotification(error.message || 'Error al actualizar el estado', 'error');
        });
    }

    // Función para actualizar el estado de un mantenimiento
    function updateMaintenanceStatus(maintenanceId, status, selectElement) {
        fetch('/user-machines/update-maintenance-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `maintenance_id=${maintenanceId}&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectElement.dataset.currentStatus = status;
                // Actualizar el estilo del select según el nuevo estado
                updateStatusStyle(selectElement, status);
                showNotification('Estado actualizado correctamente', 'success');
            } else {
                throw new Error(data.message || 'Error al actualizar el estado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            selectElement.value = selectElement.dataset.currentStatus;
            showNotification(error.message, 'error');
        });
    }

    // Función para actualizar el estilo del select según el estado
    function updateStatusStyle(selectElement, status) {
        selectElement.className = selectElement.className.replace(/bg-\w+-100 text-\w+-800/g, '');
        switch (status) {
            case 'pending':
                selectElement.classList.add('bg-yellow-100', 'text-yellow-800');
                break;
            case 'in_progress':
                selectElement.classList.add('bg-blue-100', 'text-blue-800');
                break;
            case 'resolved':
            case 'completed':
                selectElement.classList.add('bg-green-100', 'text-green-800');
                break;
        }
    }

    // Función para mostrar notificaciones
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} shadow-lg`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Inicializar los estilos de los selectores
    document.querySelectorAll('.status-selector').forEach(select => {
        updateStatusStyle(select, select.value);
    });
});
</script>

<?php include $viewsPath . "/layouts/footer.php"; ?>

    // Función para mostrar notificaciones toast
    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) return;

        const toast = document.createElement('div');
        toast.className = `flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow ${
            type === 'error' ? 'border-l-4 border-red-500' : 'border-l-4 border-blue-500'
        }`;

        toast.innerHTML = `
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ${
                type === 'error' ? 'text-red-500 bg-red-100' : 'text-blue-500 bg-blue-100'
            } rounded-lg">
                ${
                    type === 'error' 
                    ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path></svg>'
                    : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>'
                }
            </div>
            <div class="ml-3 text-sm font-normal">${message}</div>
        `;

        toastContainer.appendChild(toast);

        // Eliminar el toast después de 3 segundos
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Función para cargar el historial de incidencias
    async function loadIncidentHistory(machineId) {
        try {
            console.log('Cargando historial para máquina:', machineId);
            
            const response = await fetch(`/history/incidents/${machineId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Importante para las cookies de sesión
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
        console.log('Datos de la respuesta:', data);
            
            if (!data.success) {
                throw new Error(data.message || 'Error al cargar el historial');
            }

            // Verificar que los datos necesarios existen
            if (!data.machine || !data.data) {
                throw new Error('Datos incompletos en la respuesta');
            }

        // Actualizar la interfaz
            updateMachineInfo(data.machine);
            updateIncidentsList(data.data);
            
        } catch (error) {
            console.error('Error completo:', error);
            showToast(`Error: ${error.message}`, 'error');
        }
    }

    // Función para actualizar la información de la máquina
    function updateMachineInfo(machine) {
        const machineInfoDiv = document.getElementById('machine-info');
        if (!machineInfoDiv) return;

    // Asegurarse de que machine no sea undefined
    if (!machine) {
        machineInfoDiv.innerHTML = '<p class="text-red-500">Error: No se pudo cargar la información de la máquina</p>';
        return;
    }

        machineInfoDiv.innerHTML = `
        <h2 class="text-xl font-bold mb-4">${machine.name || 'Sin nombre'}</h2>
            <div class="space-y-2">
                <p><span class="font-semibold">Modelo:</span> ${machine.model || 'No especificado'}</p>
                <p><span class="font-semibold">Fabricante:</span> ${machine.manufacturer || 'No especificado'}</p>
                <p><span class="font-semibold">Ubicación:</span> ${machine.location || 'No especificada'}</p>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-blue-600">Total Incidencias</p>
                <p class="text-2xl font-bold text-blue-700">${machine.total_incidents || 0}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <p class="text-sm text-yellow-600">Pendientes</p>
                <p class="text-2xl font-bold text-yellow-700">${machine.pending_incidents || 0}</p>
                </div>
                <div class="bg-orange-50 p-3 rounded-lg">
                    <p class="text-sm text-orange-600">En Progreso</p>
                <p class="text-2xl font-bold text-orange-700">${machine.in_progress_incidents || 0}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <p class="text-sm text-green-600">Resueltas</p>
                <p class="text-2xl font-bold text-green-700">${machine.resolved_incidents || 0}</p>
                </div>
            </div>
        `;
    }

    // Función auxiliar para obtener la clase CSS según el estado
    function getStatusClass(status) {
        const classes = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'resolved': 'bg-green-100 text-green-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    // Función auxiliar para obtener el texto del estado
    function getStatusText(status) {
        const texts = {
            'pending': 'Pendiente',
            'in_progress': 'En Progreso',
            'resolved': 'Resuelto'
        };
        return texts[status] || status;
    }

    // Función auxiliar para formatear fechas
    function formatDate(dateString) {
        if (!dateString) return 'Fecha no disponible';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Función para actualizar la lista de incidencias
    function updateIncidentsList(incidents) {
        const incidentsListDiv = document.getElementById('incidents-list');
        if (!incidentsListDiv) return;

        if (!incidents || incidents.length === 0) {
            incidentsListDiv.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <p>No hay incidencias registradas para esta máquina</p>
                </div>
            `;
            return;
        }

        const incidentsList = incidents.map(incident => `
            <div class="border-b border-gray-200 py-4 last:border-0">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-semibold text-lg">${incident.description || 'Sin descripción'}</h3>
                    <span class="px-3 py-1 rounded-full text-sm ${getStatusClass(incident.status)}">
                        ${getStatusText(incident.status)}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <p><span class="font-medium">Prioridad:</span> ${incident.priority || 'No especificada'}</p>
                    <p><span class="font-medium">Técnico:</span> ${incident.technician_name || 'No asignado'}</p>
                    <p><span class="font-medium">Fecha:</span> ${formatDate(incident.registered_date)}</p>
                </div>
            </div>
        `).join('');

        incidentsListDiv.innerHTML = incidentsList;
    }

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
        const machineSelect = document.getElementById('machine-select');
        if (!machineSelect) return;

        console.log('Inicializando historial de incidencias...');
        
        machineSelect.addEventListener('change', async function() {
            const machineId = this.value;
            console.log('Máquina seleccionada:', machineId);
            
            if (!machineId) {
                document.getElementById('machine-info').innerHTML = '';
                document.getElementById('incidents-list').innerHTML = '';
                return;
            }

            await loadIncidentHistory(machineId);
        });
});

// Funciones auxiliares existentes (getStatusClass, getStatusText, formatDate, etc.)


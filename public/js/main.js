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

        if (!machine) {
            const errorP = document.createElement('p');
            errorP.className = 'text-red-500';
            errorP.textContent = 'Error: No se pudo cargar la información de la máquina';
            machineInfoDiv.innerHTML = '';
            machineInfoDiv.appendChild(errorP);
            return;
        }

        // Crear elementos manualmente
        const fragment = document.createDocumentFragment();

        // Título
        const title = document.createElement('h2');
        title.className = 'text-xl font-bold mb-4';
        title.textContent = machine.name || 'Sin nombre';
        fragment.appendChild(title);

        // Información básica
        const infoDiv = document.createElement('div');
        infoDiv.className = 'space-y-2';

        const createInfoP = (label, value) => {
            const p = document.createElement('p');
            const span = document.createElement('span');
            span.className = 'font-semibold';
            span.textContent = label + ': ';
            p.appendChild(span);
            p.appendChild(document.createTextNode(value || 'No especificado'));
            return p;
        };

        infoDiv.appendChild(createInfoP('Modelo', machine.model));
        infoDiv.appendChild(createInfoP('Fabricante', machine.manufacturer));
        infoDiv.appendChild(createInfoP('Ubicación', machine.location));
        fragment.appendChild(infoDiv);

        // Estadísticas
        const statsDiv = document.createElement('div');
        statsDiv.className = 'mt-6 grid grid-cols-2 gap-4';

        const createStatDiv = (label, value, colorClass) => {
            const div = document.createElement('div');
            div.className = `${colorClass} p-3 rounded-lg`;
            
            const labelP = document.createElement('p');
            labelP.className = `text-sm ${colorClass.replace('bg-', 'text-').replace('-50', '-600')}`;
            labelP.textContent = label;
            
            const valueP = document.createElement('p');
            valueP.className = `text-2xl font-bold ${colorClass.replace('bg-', 'text-').replace('-50', '-700')}`;
            valueP.textContent = value || '0';
            
            div.appendChild(labelP);
            div.appendChild(valueP);
            return div;
        };

        statsDiv.appendChild(createStatDiv('Total Incidencias', machine.total_incidents, 'bg-blue-50'));
        statsDiv.appendChild(createStatDiv('Pendientes', machine.pending_incidents, 'bg-yellow-50'));
        statsDiv.appendChild(createStatDiv('En Progreso', machine.in_progress_incidents, 'bg-orange-50'));
        statsDiv.appendChild(createStatDiv('Resueltas', machine.resolved_incidents, 'bg-green-50'));

        fragment.appendChild(statsDiv);

        // Actualizar el contenido
        machineInfoDiv.innerHTML = '';
        machineInfoDiv.appendChild(fragment);
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

        // Crear elementos manualmente en lugar de usar template strings
        const fragment = document.createDocumentFragment();

        incidents.forEach(incident => {
            const incidentDiv = document.createElement('div');
            incidentDiv.className = 'border-b border-gray-200 py-4 last:border-0';

            const headerDiv = document.createElement('div');
            headerDiv.className = 'flex justify-between items-start mb-2';

            const title = document.createElement('h3');
            title.className = 'font-semibold text-lg';
            title.textContent = incident.description || 'Sin descripción';

            const status = document.createElement('span');
            status.className = `px-3 py-1 rounded-full text-sm ${getStatusClass(incident.status)}`;
            status.textContent = getStatusText(incident.status);

            headerDiv.appendChild(title);
            headerDiv.appendChild(status);

            const detailsDiv = document.createElement('div');
            detailsDiv.className = 'grid grid-cols-2 gap-4 text-sm';

            const priority = document.createElement('p');
            const priorityLabel = document.createElement('span');
            priorityLabel.className = 'font-medium';
            priorityLabel.textContent = 'Prioridad: ';
            priority.appendChild(priorityLabel);
            priority.appendChild(document.createTextNode(incident.priority || 'No especificada'));

            const technician = document.createElement('p');
            const technicianLabel = document.createElement('span');
            technicianLabel.className = 'font-medium';
            technicianLabel.textContent = 'Técnico: ';
            technician.appendChild(technicianLabel);
            technician.appendChild(document.createTextNode(incident.technician_name || 'No asignado'));

            const date = document.createElement('p');
            const dateLabel = document.createElement('span');
            dateLabel.className = 'font-medium';
            dateLabel.textContent = 'Fecha: ';
            date.appendChild(dateLabel);
            date.appendChild(document.createTextNode(formatDate(incident.registered_date)));

            detailsDiv.appendChild(priority);
            detailsDiv.appendChild(technician);
            detailsDiv.appendChild(date);

            incidentDiv.appendChild(headerDiv);
            incidentDiv.appendChild(detailsDiv);

            fragment.appendChild(incidentDiv);
        });

        // Limpiar y añadir el nuevo contenido
        incidentsListDiv.innerHTML = '';
        incidentsListDiv.appendChild(fragment);
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


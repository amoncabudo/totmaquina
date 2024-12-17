// Verificar si jQuery está disponible
if (typeof jQuery === 'undefined') {
    console.error('jQuery no está cargado. Algunas funcionalidades podrían no estar disponibles.');
}

// Verificar si Fancybox está disponible
if (typeof Fancybox === 'undefined') {
    console.error('Fancybox no está cargado. La previsualización de imágenes no estará disponible.');
}

// Función para manejar el scroll
function handleScroll() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 0) {
        navbar.classList.remove('bg-black');
        navbar.classList.add('bg-black/90', 'backdrop-blur-sm');
    } else {
        navbar.classList.remove('bg-black/90', 'backdrop-blur-sm');
        navbar.classList.add('bg-black');
    }
}

// Agregar el evento de scroll
window.addEventListener('scroll', handleScroll);

// Ejecutar una vez al cargar para establecer el estado inicial
handleScroll();

// Función segura para inicializar Fancybox
function initFancybox() {
    if (typeof Fancybox !== 'undefined') {
        Fancybox.bind('[data-fancybox]', {
            // Opciones de Fancybox aquí
        });
    }
}

// Función segura para usar jQuery
function initJQuery() {
    if (typeof jQuery !== 'undefined') {
        $(document).ready(function() {
            // Código que usa jQuery aquí
        });
    }
}

// Inicializar cuando el DOM esté listo (sin depender de jQuery)
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Fancybox si está disponible
    initFancybox();
    
    // Inicializar jQuery si está disponible
    initJQuery();

    // Inicialización del menú móvil y dropdown
    const overlay = document.getElementById('navbar-mobile-overlay');
    const mobileMenu = document.getElementById('navbar-mobile');
    const menuButton = document.querySelector('[data-collapse-toggle="navbar-mobile"]');
    const closeButton = document.getElementById('close-menu');
    const dropdownButton = document.getElementById('dropdownDefaultButton');
    const dropdownMenu = document.getElementById('dropdown');

    // Función para abrir el menú
    function openMenu() {
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        mobileMenu.classList.remove('-translate-x-full');
        document.body.style.overflow = 'hidden';
    }

    // Función para cerrar el menú
    function closeMenu() {
        overlay.classList.add('opacity-0', 'pointer-events-none');
        mobileMenu.classList.add('-translate-x-full');
        document.body.style.overflow = '';
        // Cerrar también el dropdown
        dropdownMenu?.classList.add('hidden');
    }

    // Función para alternar el dropdown
    function toggleDropdown(event) {
        event.stopPropagation();
        dropdownMenu?.classList.toggle('hidden');
    }

    // Event listeners
    menuButton?.addEventListener('click', openMenu);
    closeButton?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeMenu();
        }
    });
    dropdownButton?.addEventListener('click', toggleDropdown);

    // Prevenir que los clics dentro del menú cierren el overlay
    mobileMenu?.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Carrusel
    const slides = document.querySelectorAll('.carousel-slide');
    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        if (index >= slides.length) index = 0;
        if (index < 0) index = slides.length - 1;
        slides[index].classList.add('active');
        currentSlide = index;
    }

    // Función global para mover slides
    window.moveSlide = function(direction) {
        showSlide(currentSlide + direction);
        resetInterval();
    };

    function startInterval() {
        slideInterval = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 6000);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }

    // Inicializar el carrusel
    if (slides.length > 0) {
        showSlide(0);
        startInterval();

        // Pausar en hover
        const container = document.querySelector('.carousel-container');
        container?.addEventListener('mouseenter', () => clearInterval(slideInterval));
        container?.addEventListener('mouseleave', startInterval);
    }

    // Inicializar Fancybox para la galería
    if (typeof Fancybox !== 'undefined') {
        Fancybox.bind('[data-fancybox="gallery"]', {
            Carousel: {
                infinite: true
            },
            Toolbar: {
                display: ["close"]
            }
        });
    }

    // Función para actualizar técnicos en la base de datos
    async function updateTechniciansInDatabase(technicianIds, maintenanceId) {
        try {
            if (!maintenanceId) {
                console.error('No se proporcionó el ID del mantenimiento');
                return;
            }

            console.log('Actualizando técnicos para mantenimiento:', maintenanceId);
            console.log('Técnicos a asignar:', technicianIds);

            const response = await fetch('/maintenance/update-technicians', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    maintenance_id: maintenanceId,
                    technician_ids: technicianIds
                })
            });

            const data = await response.json();
            if (data.success) {
                showToast('Técnicos actualizados correctamente', 'success');
            } else {
                throw new Error(data.message || 'Error al actualizar técnicos');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(`Error: ${error.message}`, 'error');
        }
    }

    // Inicialización del drag and drop de técnicos
    function initTechnicianDragDrop() {
        const disponibles = document.getElementById('tecnicos-disponibles');
        const asignados = document.getElementById('tecnicos-asignados');
        
        console.log('Intentando inicializar drag and drop...');
        
        if (!disponibles || !asignados) {
            console.log('No se encontraron las listas necesarias para el drag and drop');
            return;
        }

        // Determinar si estamos en la página de incidencias o mantenimiento
        const isIncidentsPage = window.location.pathname.includes('incidents');
        console.log('Es página de incidencias:', isIncidentsPage);

        try {
            // Inicializar Sortable para la lista de disponibles
            new Sortable(disponibles, {
                group: 'tecnicos',
                animation: 150,
                sort: false,
                ghostClass: 'bg-blue-100',
                chosenClass: 'bg-blue-200',
                dragClass: 'opacity-50'
            });

            // Inicializar Sortable para la lista de asignados
            new Sortable(asignados, {
                group: 'tecnicos',
                animation: 150,
                sort: false,
                ghostClass: 'bg-blue-100',
                chosenClass: 'bg-blue-200',
                dragClass: 'opacity-50',
                onAdd: function(evt) {
                    if (isIncidentsPage) {
                        // Para incidencias, solo permitir un técnico
                        const items = asignados.children;
                        if (items.length > 1) {
                            // Si ya hay un técnico, devolver el nuevo al contenedor original
                            disponibles.appendChild(items[0]);
                        }
                    }
                    updateTechniciansData();
                },
                onRemove: function(evt) {
                    updateTechniciansData();
                }
            });

            console.log('Drag and drop inicializado correctamente');

        } catch (error) {
            console.error('Error al inicializar drag and drop:', error);
        }
    }

    // Función para actualizar el campo oculto con los datos de los técnicos
    function updateTechniciansData() {
        const asignados = document.getElementById('tecnicos-asignados');
        const techniciansDataInput = document.getElementById('technicians-data');
        
        if (asignados && techniciansDataInput) {
            const assignedTechnicians = Array.from(asignados.children);
            
            // Para incidencias, solo tomamos el primer técnico asignado
            if (window.location.pathname.includes('incidents')) {
                if (assignedTechnicians.length > 0) {
                    techniciansDataInput.value = assignedTechnicians[0].dataset.id;
                    // Remover cualquier técnico adicional
                    while (assignedTechnicians.length > 1) {
                        asignados.removeChild(assignedTechnicians[assignedTechnicians.length - 1]);
                    }
                } else {
                    techniciansDataInput.value = '';
                }
            } else {
                // Para mantenimiento, tomamos todos los técnicos
                const technicianIds = assignedTechnicians.map(li => li.dataset.id);
                techniciansDataInput.value = JSON.stringify(technicianIds);
            }
        }
    }

    // Inicializar el manejo del formulario de mantenimiento
    const maintenanceForm = document.querySelector('form[action="/maintenance/create"]');
    if (maintenanceForm) {
        maintenanceForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                
                // Obtener los técnicos asignados
                const asignados = document.getElementById('tecnicos-asignados');
                if (asignados) {
                    const technicianIds = Array.from(asignados.children).map(li => li.dataset.id);
                    formData.delete('technicians[]'); // Eliminar valores anteriores si existen
                    technicianIds.forEach(id => {
                        formData.append('technicians[]', id);
                    });
                }

                const response = await fetch('/maintenance/create', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message || 'Mantenimiento creado correctamente', 'success');
                    // Esperar un momento antes de recargar para que se vea el mensaje
                    setTimeout(() => {
                        window.location.href = '/maintenance'; // Redirigir a la lista de mantenimientos
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Error al crear el mantenimiento');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(`Error: ${error.message}`, 'error');
            }
        });
    }

    // Inicializar el drag and drop de técnicos
    initTechnicianDragDrop();

    // Event listener para el botón de reset si existe
    const resetButton = document.getElementById('reset-technicians');
    if (resetButton) {
        resetButton.addEventListener('click', resetTechnicians);
    }

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

    // Manejar el formulario de consulta de historial
    const historyForm = document.getElementById('maintenance-history-form');
    if (historyForm) {
        historyForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevenir el envío tradicional del formulario
            
            const machineSelect = this.querySelector('select[name="machine_id"]');
            if (!machineSelect) return;

            const machineId = machineSelect.value;
            if (!machineId) {
                showToast('Por favor, seleccione una máquina', 'error');
                return;
            }

            await loadMaintenanceHistory(machineId);
        });
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

    // Función para cargar el historial de mantenimiento
    async function loadMaintenanceHistory(machineId) {
        try {
            console.log('Cargando historial de mantenimiento para máquina:', machineId);
            
            const response = await fetch(`/maintenance-history/get/${machineId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            // Log para debug
            console.log('URL:', `/maintenance-history/get/${machineId}`);
            console.log('Response status:', response.status);
            const responseText = await response.text();
            console.log('Response text:', responseText);

            try {
                const data = JSON.parse(responseText);
                console.log('Datos del historial de mantenimiento:', data);

                if (!data.success) {
                    throw new Error(data.message || 'Error al cargar el historial de mantenimiento');
                }

                updateMaintenanceHistory(data.history);
            } catch (parseError) {
                console.error('Error parsing JSON:', parseError);
                throw new Error('La respuesta del servidor no es JSON válido');
            }
            
        } catch (error) {
            console.error('Error al cargar el historial de mantenimiento:', error);
            showToast(`Error: ${error.message}`, 'error');
        }
    }

    // Función para actualizar la vista del historial de mantenimiento
    function updateMaintenanceHistory(history) {
        const historyContainer = document.getElementById('maintenance-history');
        if (!historyContainer) return;

        if (!history || history.length === 0) {
            historyContainer.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <p>No hay registros de mantenimiento para esta máquina</p>
                </div>
            `;
            return;
        }

        const fragment = document.createDocumentFragment();

        history.forEach(record => {
            const recordDiv = document.createElement('div');
            recordDiv.className = 'border-b border-gray-200 py-4 last:border-0';

            const headerDiv = document.createElement('div');
            headerDiv.className = 'flex justify-between items-start mb-2';

            const title = document.createElement('h3');
            title.className = 'font-semibold text-lg';
            title.textContent = record.description || 'Sin descripción';

            const status = document.createElement('span');
            status.className = `px-3 py-1 rounded-full text-sm ${getMaintenanceStatusClass(record.status)}`;
            status.textContent = getMaintenanceStatusText(record.status);

            headerDiv.appendChild(title);
            headerDiv.appendChild(status);

            const detailsDiv = document.createElement('div');
            detailsDiv.className = 'grid grid-cols-2 gap-4 text-sm';

            const type = document.createElement('p');
            type.innerHTML = `<span class="font-medium">Tipo:</span> ${getMaintenanceTypeText(record.type)}`;

            const frequency = document.createElement('p');
            frequency.innerHTML = `<span class="font-medium">Frecuencia:</span> ${getFrequencyText(record.frequency)}`;

            const date = document.createElement('p');
            date.innerHTML = `<span class="font-medium">Fecha programada:</span> ${formatDate(record.scheduled_date)}`;

            const technicians = document.createElement('p');
            technicians.innerHTML = `<span class="font-medium">Técnicos:</span> ${record.technicians || 'No asignados'}`;

            detailsDiv.appendChild(type);
            detailsDiv.appendChild(frequency);
            detailsDiv.appendChild(date);
            detailsDiv.appendChild(technicians);

            recordDiv.appendChild(headerDiv);
            recordDiv.appendChild(detailsDiv);

            fragment.appendChild(recordDiv);
        });

        historyContainer.innerHTML = '';
        historyContainer.appendChild(fragment);
    }

    // Funciones auxiliares
    function getMaintenanceStatusClass(status) {
        const classes = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    function getMaintenanceStatusText(status) {
        const texts = {
            'pending': 'Pendiente',
            'in_progress': 'En Progreso',
            'completed': 'Completado'
        };
        return texts[status] || status;
    }

    function getMaintenanceTypeText(type) {
        const texts = {
            'preventive': 'Preventivo',
            'corrective': 'Correctivo'
        };
        return texts[type] || type;
    }

    function getFrequencyText(frequency) {
        const texts = {
            'weekly': 'Semanal',
            'monthly': 'Mensual',
            'quarterly': 'Trimestral',
            'yearly': 'Anual'
        };
        return texts[frequency] || frequency;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el drag and drop para técnicos
        initTechnicianDragAndDrop();
    });

    function initTechnicianDragAndDrop() {
        const technicians = document.querySelectorAll('.technician-item');
        const maintenanceCards = document.querySelectorAll('.maintenance-card');

        // Hacer los técnicos arrastrables
        technicians.forEach(tech => {
            tech.setAttribute('draggable', true);
            tech.addEventListener('dragstart', handleDragStart);
            tech.addEventListener('dragend', handleDragEnd);
        });

        // Configurar las zonas donde se pueden soltar los técnicos
        maintenanceCards.forEach(card => {
            card.addEventListener('dragover', handleDragOver);
            card.addEventListener('drop', handleDrop);
            card.addEventListener('dragenter', handleDragEnter);
            card.addEventListener('dragleave', handleDragLeave);
        });
    }

    function handleDragStart(e) {
        e.target.classList.add('dragging');
        e.dataTransfer.setData('text/plain', JSON.stringify({
            technicianId: e.target.dataset.technicianId,
            technicianName: e.target.dataset.technicianName
        }));
    }

    function handleDragEnd(e) {
        e.target.classList.remove('dragging');
    }

    function handleDragOver(e) {
        e.preventDefault();
    }

    function handleDragEnter(e) {
        e.preventDefault();
        e.currentTarget.classList.add('drag-over');
    }

    function handleDragLeave(e) {
        e.currentTarget.classList.remove('drag-over');
    }

    async function handleDrop(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('drag-over');

        const maintenanceCard = e.currentTarget;
        const maintenanceId = maintenanceCard.dataset.maintenanceId;
        const technicianData = JSON.parse(e.dataTransfer.getData('text/plain'));

        try {
            const response = await fetch('/maintenance/assign-technician', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    maintenance_id: maintenanceId,
                    technician_id: technicianData.technicianId
                })
            });

            const data = await response.json();

            if (data.success) {
                // Actualizar la UI
                updateTechniciansList(maintenanceCard, technicianData);
                showToast('Técnico asignado correctamente', 'success');
            } else {
                throw new Error(data.message || 'Error al asignar técnico');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(`Error: ${error.message}`, 'error');
        }
    }

    function updateTechniciansList(maintenanceCard, technicianData) {
        const techList = maintenanceCard.querySelector('.technicians-list');
        
        // Verificar si el técnico ya está asignado
        const existingTech = techList.querySelector(`[data-technician-id="${technicianData.technicianId}"]`);
        if (existingTech) return;

        // Crear el nuevo elemento del técnico
        const techItem = document.createElement('div');
        techItem.className = 'flex items-center gap-2 bg-gray-100 rounded p-2 text-sm';
        techItem.dataset.technicianId = technicianData.technicianId;
        
        techItem.innerHTML = `
            <span>${technicianData.technicianName}</span>
            <button onclick="removeTechnician(this, ${technicianData.technicianId}, ${maintenanceCard.dataset.maintenanceId})" 
                    class="text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        techList.appendChild(techItem);
    }

    async function removeTechnician(button, technicianId, maintenanceId) {
        try {
            const response = await fetch('/maintenance/remove-technician', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    maintenance_id: maintenanceId,
                    technician_id: technicianId
                })
            });

            const data = await response.json();

            if (data.success) {
                button.closest('[data-technician-id]').remove();
                showToast('Técnico eliminado correctamente', 'success');
            } else {
                throw new Error(data.message || 'Error al eliminar técnico');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(`Error: ${error.message}`, 'error');
        }
    }

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

    // Función para resetear técnicos
    async function resetTechnicians() {
        // Si estamos en la página de incidencias, solo resetear el select
        if (window.location.pathname.includes('incidents')) {
            const select = document.getElementById('responsible_technician_id');
            if (select) {
                select.value = '';
            }
            return;
        }

        // Código existente para mantenimiento
        const disponibles = document.getElementById('tecnicos-disponibles');
        const asignados = document.getElementById('tecnicos-asignados');
        const maintenanceId = document.getElementById('maintenance_id')?.value;

        if (disponibles && asignados && maintenanceId) {
            try {
                const response = await fetch('/maintenance/reset-technicians', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        maintenance_id: maintenanceId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    // Mover todos los técnicos de vuelta a disponibles
                    while (asignados.firstChild) {
                        disponibles.appendChild(asignados.firstChild);
                    }
                    showToast('Técnicos reseteados correctamente', 'success');
                } else {
                    throw new Error(data.message || 'Error al resetear técnicos');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(`Error: ${error.message}`, 'error');
            }
        }
    }

    // Función para inicializar el drag and drop
    function initDragAndDrop() {
        const disponibles = document.getElementById('tecnicos-disponibles');
        const asignados = document.getElementById('tecnicos-asignados');
        const maintenanceId = document.querySelector('input[name="maintenance_id"]')?.value;

        if (!disponibles || !asignados) {
            console.log('No se encontraron las listas necesarias');
            return;
        }

        if (!maintenanceId) {
            console.log('No se encontró el ID del mantenimiento');
            return;
        }

        // Inicializar Sortable para la lista de disponibles
        new Sortable(disponibles, {
            group: 'tecnicos',
            animation: 150,
            ghostClass: 'bg-blue-100',
            chosenClass: 'bg-blue-200',
            dragClass: 'opacity-50'
        });

        // Inicializar Sortable para la lista de asignados
        new Sortable(asignados, {
            group: 'tecnicos',
            animation: 150,
            ghostClass: 'bg-blue-100',
            chosenClass: 'bg-blue-200',
            dragClass: 'opacity-50',
            onAdd: function(evt) {
                const technicianIds = Array.from(asignados.children).map(li => li.dataset.id);
                updateTechnicians(technicianIds, maintenanceId);
            },
            onRemove: function(evt) {
                const technicianIds = Array.from(asignados.children).map(li => li.dataset.id);
                updateTechnicians(technicianIds, maintenanceId);
            }
        });
    }

    // Función para actualizar técnicos en el servidor
    async function updateTechnicians(technicianIds, maintenanceId) {
        try {
            const response = await fetch('/maintenance/update-technicians', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    maintenance_id: maintenanceId,
                    technician_ids: technicianIds
                })
            });

            const data = await response.json();
            if (data.success) {
                showToast('Técnicos actualizados correctamente', 'success');
            } else {
                throw new Error(data.message || 'Error al actualizar técnicos');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message, 'error');
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el drag and drop
        initDragAndDrop();

        // Manejar el formulario de mantenimiento
        const form = document.querySelector('form[action="/maintenance/create"]');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const formData = new FormData(this);
                    const asignados = document.getElementById('tecnicos-asignados');
                    
                    if (asignados) {
                        const technicianIds = Array.from(asignados.children).map(li => li.dataset.id);
                        formData.delete('technicians[]');
                        technicianIds.forEach(id => formData.append('technicians[]', id));
                    }

                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showToast('Mantenimiento creado correctamente', 'success');
                        setTimeout(() => {
                            window.location.href = '/maintenance';
                        }, 1500);
                    } else {
                        throw new Error(result.message || 'Error al crear el mantenimiento');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast(error.message, 'error');
                }
            });
        }
    });
});
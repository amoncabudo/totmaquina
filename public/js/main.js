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

    // Inicialización del menú móvil y dropdown cuando el DOM está cargado
    document.addEventListener('DOMContentLoaded', function() {
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
            dropdownMenu.classList.add('hidden');
        }

        // Función para alternar el dropdown
        function toggleDropdown(event) {
            event.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
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
            container.addEventListener('mouseenter', () => clearInterval(slideInterval));
            container.addEventListener('mouseleave', startInterval);
        }

        // Inicializar Fancybox
        Fancybox.bind('[data-fancybox="gallery"]', {
            Carousel: {
                infinite: true
            },
            Toolbar: {
                display: ["close"]
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Fancybox
            Fancybox.bind('[data-fancybox="gallery"]', {
                Carousel: {
                    infinite: true
                },
                Toolbar: {
                    display: ["close"]
                }
            });
        
            // Variables del carrusel
            const slides = document.querySelectorAll('.carousel-slide');
            let currentSlide = 0;
            let slideInterval;
        
            // Función para mostrar una slide
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
        
            // Funciones para el autoplay
            function startInterval() {
                slideInterval = setInterval(() => {
                    showSlide(currentSlide + 1);
                }, 6000);
            }
        
            function resetInterval() {
                clearInterval(slideInterval);
                startInterval();
            }
        
            // Inicializar carrusel
            showSlide(0);
            startInterval();
        
            // Pausar en hover
            const container = document.querySelector('.carousel-container');
            container.addEventListener('mouseenter', () => clearInterval(slideInterval));
            container.addEventListener('mouseleave', startInterval);
        });
    });

    // Función para manejar la accesibilidad del teclado en las listas de técnicos
    function setupKeyboardAccessibility(sourceList, targetList) {
        const lists = [sourceList, targetList];
        lists.forEach(list => {
            list.addEventListener('keydown', (e) => {
                const items = Array.from(list.children);
                const currentIndex = items.indexOf(document.activeElement);

                switch(e.key) {
                    case 'ArrowUp':
                        e.preventDefault();
                        if (currentIndex > 0) {
                            items[currentIndex - 1].focus();
                        }
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        if (currentIndex < items.length - 1) {
                            items[currentIndex + 1].focus();
                        }
                        break;
                    case 'Space':
                    case 'Enter':
                        e.preventDefault();
                        const item = document.activeElement;
                        if (item.tagName === 'LI') {
                            const targetListId = list.id === 'tecnicos-disponibles' ? 'tecnicos-asignados' : 'tecnicos-disponibles';
                            document.getElementById(targetListId).appendChild(item);
                            item.focus();
                            // Anunciar el cambio para lectores de pantalla
                            announceChange(item.textContent, list.id === 'tecnicos-disponibles' ? 'asignado' : 'removido');
                        }
                        break;
                }
            });
        });
    }

    // Función para anunciar cambios a lectores de pantalla
    function announceChange(tecnico, action) {
        const announcement = document.createElement('div');
        announcement.setAttribute('role', 'status');
        announcement.setAttribute('aria-live', 'polite');
        announcement.className = 'sr-only';
        announcement.textContent = `Técnico ${tecnico} ${action}`;
        document.body.appendChild(announcement);
        setTimeout(() => announcement.remove(), 1000);
    }

    // Función para manejar la navegación por teclado
    function handleKeyboardNavigation(e, item) {
        const list = item.parentElement;
        const items = Array.from(list.children);
        const currentIndex = items.indexOf(item);

        switch(e.key) {
            case 'ArrowUp':
                e.preventDefault();
                if (currentIndex > 0) {
                    items[currentIndex - 1].focus();
                }
                break;
            case 'ArrowDown':
                e.preventDefault();
                if (currentIndex < items.length - 1) {
                    items[currentIndex + 1].focus();
                }
                break;
            case 'Space':
            case 'Enter':
                e.preventDefault();
                const targetList = list.id === 'tecnicos-disponibles' ? 
                    document.getElementById('tecnicos-asignados') : 
                    document.getElementById('tecnicos-disponibles');
                targetList.appendChild(item);
                item.focus();
                break;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Configuración de Sortable con accesibilidad
        const sortableOptions = {
            animation: 300,
            ghostClass: 'bg-blue-100',
            chosenClass: 'bg-blue-200',
            dragClass: 'shadow-lg',
            onStart: function (evt) {
                evt.item.setAttribute('aria-grabbed', 'true');
                evt.item.classList.add('scale-105', 'shadow-xl');
            },
            onEnd: function (evt) {
                evt.item.setAttribute('aria-grabbed', 'false');
                evt.item.classList.remove('scale-105', 'shadow-xl');
                
                // Anunciar el cambio para lectores de pantalla
                const announcement = document.createElement('div');
                announcement.setAttribute('role', 'status');
                announcement.setAttribute('aria-live', 'polite');
                announcement.className = 'sr-only';
                announcement.textContent = `Técnico ${evt.item.textContent} ${evt.to.id === 'tecnicos-asignados' ? 'asignado' : 'removido'}`;
                document.body.appendChild(announcement);
                setTimeout(() => announcement.remove(), 1000);
            }
        };

        // Inicializar Sortable para las listas
        if(document.getElementById('tecnicos-disponibles')) {
            new Sortable(document.getElementById('tecnicos-disponibles'), {
                ...sortableOptions,
                group: 'tecnicos'
            });

            // Añadir eventos de teclado
            const items = document.querySelectorAll('#tecnicos-disponibles li');
            items.forEach(item => {
                item.addEventListener('keydown', (e) => handleKeyboardNavigation(e, item));
            });
        }

        if(document.getElementById('tecnicos-asignados')) {
            new Sortable(document.getElementById('tecnicos-asignados'), {
                ...sortableOptions,
                group: 'tecnicos'
            });

            // Añadir eventos de teclado
            const items = document.querySelectorAll('#tecnicos-asignados li');
            items.forEach(item => {
                item.addEventListener('keydown', (e) => handleKeyboardNavigation(e, item));
            });
        }

        // Inicializar gráficos con opciones de accesibilidad
        if(document.getElementById('deviceChart')) {
            const deviceChart = new Chart(document.getElementById('deviceChart'), {
                type: 'pie',
                data: {
                    labels: ['PC', 'Impresoras', 'Servidores', 'Red'],
                    datasets: [{
                        data: [12, 19, 3, 5],
                        backgroundColor: ['#4B5563', '#60A5FA', '#34D399', '#F87171']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 14 },
                                color: '#000000'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 14 },
                            bodyFont: { size: 14 }
                        }
                    }
                }
            });
        }

        // Validación de formulario accesible
        const form = document.getElementById('incidenciaForm');
        if(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Limpiar mensajes de error anteriores
                form.querySelectorAll('.error-message').forEach(msg => msg.remove());
                
                // Validar campos requeridos
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        const errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message text-red-600 text-sm mt-1';
                        errorMsg.setAttribute('role', 'alert');
                        errorMsg.textContent = `Por favor, complete este campo`;
                        field.parentNode.appendChild(errorMsg);
                    }
                });

                if (isValid) {
                    // Mostrar mensaje de éxito
                    const successMsg = document.createElement('div');
                    successMsg.className = 'success-message text-green-600 text-center mt-4';
                    successMsg.setAttribute('role', 'status');
                    successMsg.setAttribute('aria-live', 'polite');
                    successMsg.textContent = 'Formulario enviado correctamente';
                    form.appendChild(successMsg);
                }
            });
        }
    });
            // Script para manejar el drag and drop de técnicos
            document.addEventListener('DOMContentLoaded', function() {
                const disponibles = document.getElementById('tecnicos-disponibles');
                const asignados = document.getElementById('tecnicos-asignados');
                const selectedTechnicians = document.getElementById('selected-technicians');
    
                if (disponibles && asignados) {
                    // Función para actualizar el campo oculto con los IDs de los técnicos asignados
                    function updateSelectedTechnicians() {
                        const assignedTechnicians = Array.from(asignados.children).map(li => li.dataset.id);
                        selectedTechnicians.value = assignedTechnicians.join(',');
                        console.log('Técnicos seleccionados:', selectedTechnicians.value); // Debug
                    }

                    // Inicializar Sortable para la lista de disponibles
                    new Sortable(disponibles, {
                        group: 'tecnicos',
                        animation: 150,
                        onSort: updateSelectedTechnicians,
                        onAdd: updateSelectedTechnicians,
                        onRemove: updateSelectedTechnicians
                    });

                    // Inicializar Sortable para la lista de asignados
                    new Sortable(asignados, {
                        group: 'tecnicos',
                        animation: 150,
                        onSort: updateSelectedTechnicians,
                        onAdd: updateSelectedTechnicians,
                        onRemove: updateSelectedTechnicians
                    });

                    // Actualizar el campo oculto cuando se carga la página
                    updateSelectedTechnicians();
                }
            });

    // Manejo del formulario de mantenimiento
    document.addEventListener('DOMContentLoaded', function() {
        const maintenanceForm = document.querySelector('form[action="/maintenance/create"]');
        if (maintenanceForm) {
            maintenanceForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('Enviando formulario de mantenimiento');

                // Obtener los técnicos seleccionados
                const selectedTechnicians = Array.from(document.querySelectorAll('#tecnicos-asignados li'))
                    .map(li => li.getAttribute('data-id'));
                console.log('Técnicos seleccionados:', selectedTechnicians);

                // Crear FormData con los datos del formulario
                const formData = new FormData(this);
                
                // Asegurarse de que la fecha tenga el formato correcto
                const dateInput = document.getElementById('scheduled_date');
                if (dateInput && dateInput.value) {
                    formData.set('scheduled_date', dateInput.value);
                }

                // Agregar los técnicos seleccionados
                if (selectedTechnicians.length > 0) {
                    selectedTechnicians.forEach(techId => {
                        formData.append('technicians[]', techId);
                    });
                }

                try {
                    console.log('Enviando datos al servidor:', Object.fromEntries(formData));
                    
                    const response = await fetch('/maintenance/create', {
                        method: 'POST',
                        body: formData
                    });

                    console.log('Respuesta del servidor:', response);
                    console.log('Headers:', response.headers);
                    
                    // Intentar leer el texto de la respuesta primero
                    const responseText = await response.text();
                    console.log('Texto de respuesta:', responseText);

                    let result;
                    try {
                        result = JSON.parse(responseText);
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        throw new Error('La respuesta del servidor no es JSON válido. Respuesta: ' + responseText);
                    }

                    console.log('Respuesta parseada:', result);

                    if (result.success) {
                        alert(result.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(result.message || 'Error al registrar el mantenimiento');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud: ' + error.message);
                }
            });
        }
    });
    
    // Configuración de los gráficos de mantenimiento
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si estamos en la página de estadísticas
        if (document.getElementById('typeChart')) {
            // Obtener los datos de los elementos data
            const statsContainer = document.getElementById('stats-data');
            const typeData = JSON.parse(statsContainer.dataset.typeStats || '[]');
            const frequencyData = JSON.parse(statsContainer.dataset.frequencyStats || '[]');
            const monthlyData = JSON.parse(statsContainer.dataset.monthlyStats || '[]');
            const machineData = JSON.parse(statsContainer.dataset.machineStats || '[]');

            // Gráfico por Tipo
            new Chart(document.getElementById('typeChart'), {
                type: 'pie',
                data: {
                    labels: typeData.map(item => item.type === 'preventive' ? 'Preventivo' : 'Correctivo'),
                    datasets: [{
                        data: typeData.map(item => item.count),
                        backgroundColor: ['#60A5FA', '#F87171']
                    }]
                }
            });

            // Gráfico por Frecuencia
            new Chart(document.getElementById('frequencyChart'), {
                type: 'bar',
                data: {
                    labels: frequencyData.map(item => {
                        const labels = {
                            'weekly': 'Semanal',
                            'monthly': 'Mensual',
                            'quarterly': 'Trimestral',
                            'yearly': 'Anual'
                        };
                        return labels[item.frequency];
                    }),
                    datasets: [{
                        label: 'Cantidad',
                        data: frequencyData.map(item => item.count),
                        backgroundColor: '#60A5FA'
                    }]
                }
            });

            // Gráfico Mensual
            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => {
                        const date = new Date(item.month + '-01');
                        return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Mantenimientos',
                        data: monthlyData.map(item => item.count),
                        borderColor: '#60A5FA',
                        tension: 0.1
                    }]
                }
            });

            // Gráfico por Máquina
            new Chart(document.getElementById('machineChart'), {
                type: 'bar',
                data: {
                    labels: machineData.map(item => item.machine_name),
                    datasets: [{
                        label: 'Mantenimientos',
                        data: machineData.map(item => item.count),
                        backgroundColor: '#60A5FA'
                    }]
                },
                options: {
                    indexAxis: 'y'
                }
            });
        }
    });
    
    // Funcionalidad para el historial de mantenimiento
    function initMaintenanceHistory() {
        const selectMachine = document.getElementById("machine-select");
        if (!selectMachine) return;

        const infoBox = document.getElementById("machine-info");
        const maintenanceHistory = document.getElementById("maintenance-history");
        const historyContent = document.getElementById("history-content");
        const infoNombre = document.getElementById("info-nombre");
        const infoModelo = document.getElementById("info-modelo");
        const infoFabricante = document.getElementById("info-fabricante");
        const infoCoordenadas = document.getElementById("info-coordenadas");
        const infoUbicacion = document.getElementById("info-ubicacion");

        // Actualizar la información de la máquina cuando se selecciona una
        selectMachine.addEventListener("change", async function(event) {
            event.preventDefault();
            const selectedValue = selectMachine.value;
            console.log("Máquina seleccionada:", selectedValue);

            if (selectedValue) {
                try {
                    const response = await fetch(`/api/machine/${selectedValue}`);
                    console.log("Respuesta de la API de máquina:", response);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const machine = await response.json();
                    console.log("Datos de la máquina:", machine);
                    
                    if (machine) {
                        infoNombre.textContent = machine.name || 'No disponible';
                        infoModelo.textContent = machine.model || 'No disponible';
                        infoFabricante.textContent = machine.manufacturer || 'No disponible';
                        infoCoordenadas.textContent = machine.coordinates || 'No disponible';
                        infoUbicacion.textContent = machine.location || 'No disponible';
                        infoBox.classList.remove("hidden");
                    }
                } catch (error) {
                    console.error('Error al obtener información de la máquina:', error);
                    alert('Error al obtener información de la máquina. Por favor, inténtalo de nuevo.');
                }
            } else {
                infoBox.classList.add("hidden");
            }
        });

        // Manejar el envío del formulario
        const form = document.getElementById("maintenance-form");
        if (form) {
            form.addEventListener("submit", async function(event) {
                event.preventDefault();
                const selectedMachine = selectMachine.value;
                console.log("Consultando historial para máquina:", selectedMachine);
                
                if (selectedMachine) {
                    try {
                        const response = await fetch(`/api/maintenance/history/${selectedMachine}`);
                        console.log("Respuesta de la API de historial:", response);
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const history = await response.json();
                        console.log("Datos del historial:", history);
                        
                        historyContent.innerHTML = '';
                        
                        if (history && history.length > 0) {
                            history.forEach(record => {
                                const recordElement = document.createElement('div');
                                recordElement.className = 'bg-white p-4 rounded-lg shadow mb-4';
                                recordElement.innerHTML = `
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-lg mb-2">Fecha: ${new Date(record.date).toLocaleDateString('es-ES', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            })}</p>
                                            <p class="mb-1"><span class="font-medium">Tipo:</span> ${record.type}</p>
                                            <p class="mb-1"><span class="font-medium">Estado:</span> ${record.status}</p>
                                            <p class="mb-1"><span class="font-medium">Técnico:</span> ${record.technician || 'No asignado'}</p>
                                            <p class="mt-2 text-gray-600">${record.description || 'Sin descripción'}</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-sm ${
                                            record.status === 'Completado' ? 'bg-green-100 text-green-800' :
                                            record.status === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                                            record.status === 'En Proceso' ? 'bg-blue-100 text-blue-800' :
                                            'bg-gray-100 text-gray-800'
                                        }">${record.status}</span>
                                    </div>
                                `;
                                historyContent.appendChild(recordElement);
                            });
                            maintenanceHistory.classList.remove('hidden');
                        } else {
                            historyContent.innerHTML = `
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                No hay registros de mantenimiento para esta máquina.
                                            </p>
                                        </div>
                                    </div>
                                </div>`;
                            maintenanceHistory.classList.remove('hidden');
                        }
                    } catch (error) {
                        console.error('Error al obtener el historial:', error);
                        historyContent.innerHTML = `
                            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            Error al obtener el historial de mantenimiento. Por favor, inténtalo de nuevo.
                                        </p>
                                    </div>
                                </div>
                            </div>`;
                        maintenanceHistory.classList.remove('hidden');
                    }
                } else {
                    alert("Por favor, selecciona una máquina antes de continuar.");
                }
            });
        }
    }

    // Asegurarnos de que la función se ejecute cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMaintenanceHistory);
    } else {
        initMaintenanceHistory();
    }
    
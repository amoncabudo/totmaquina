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

    // Variable para rastrear si el formulario ya fue inicializado
    let isMaintenanceFormInitialized = false;

    // Función para inicializar el manejo de técnicos
    function initializeTechnicians() {
        const disponibles = document.getElementById('tecnicos-disponibles');
        const asignados = document.getElementById('tecnicos-asignados');
        const techniciansData = document.getElementById('technicians-data');

        if (disponibles && asignados && !isMaintenanceFormInitialized) {
            // Función para actualizar el campo oculto con los IDs de los técnicos asignados
            function updateSelectedTechnicians() {
                const assignedTechnicians = Array.from(asignados.children).map(li => li.dataset.id);
                if (techniciansData) {
                    techniciansData.value = assignedTechnicians.join(',');
                }
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
    }

    // Función para inicializar el formulario de mantenimiento
    function initializeMaintenanceForm() {
        if (isMaintenanceFormInitialized) return;

        const maintenanceForm = document.querySelector('form[action="/maintenance/create"]');
        if (maintenanceForm) {
            maintenanceForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Deshabilitar el botón de submit para evitar múltiples envíos
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton.disabled) return; // Si ya está deshabilitado, no continuar
                submitButton.disabled = true;

                try {
                    // Crear FormData con los datos del formulario
                    const formData = new FormData(this);
                    
                    // Obtener los técnicos seleccionados
                    const selectedTechnicians = Array.from(document.querySelectorAll('#tecnicos-asignados li'))
                        .map(li => li.getAttribute('data-id'));

                    // Limpiar los técnicos existentes del FormData
                    formData.delete('technicians[]');
                    formData.delete('technicians_data');

                    // Agregar los técnicos seleccionados
                    selectedTechnicians.forEach(techId => {
                        formData.append('technicians[]', techId);
                    });

                    const response = await fetch('/maintenance/create', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Mostrar mensaje de éxito
                        const successMessage = document.createElement('div');
                        successMessage.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                        successMessage.setAttribute('role', 'alert');
                        successMessage.innerHTML = `<span class="block sm:inline">${result.message}</span>`;
                        
                        // Eliminar mensajes anteriores si existen
                        const previousMessages = maintenanceForm.parentNode.querySelectorAll('[role="alert"]');
                        previousMessages.forEach(msg => msg.remove());
                        
                        // Insertar el nuevo mensaje
                        maintenanceForm.parentNode.insertBefore(successMessage, maintenanceForm);

                        // Recargar la página después de un breve delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(result.message || 'Error al registrar el mantenimiento');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message || 'Error al procesar la solicitud');
                } finally {
                    // Re-habilitar el botón de submit después de un breve delay
                    setTimeout(() => {
                        submitButton.disabled = false;
                    }, 2000);
                }
            });

            isMaintenanceFormInitialized = true;
        }
    }

    // Configuración de los gráficos de mantenimiento
    function initializeCharts() {
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
    }

    // Función para generar usuarios de prueba
    function initializeUserManagement() {
        $(document).ready(function() {
            console.log('DOM cargado, configurando event listeners...');
        
            $('#createTestTechnician').on('click', function() {
                generarUsuarioPrueba('technician');
            });
        
            $('#createTestSupervisor').on('click', function() {
                generarUsuarioPrueba('supervisor');
            });
        });
    }

    function generarUsuarioPrueba(role) {
        console.log('Generando usuario de prueba para el rol:', role);

        $.ajax({
            url: 'https://randomuser.me/api/?nat=es&inc=email,name,login',
            dataType: 'json',
            success: function(data) {
                const user = data.results[0];
                const usuarioPrueba = {
                    nombre: user.name.first,
                    apellido: user.name.last,
                    email: user.email,
                    pass: 'Testing10.',
                    rol: role
                };

                // Enviar al servidor
                $.ajax({
                    url: '/createTestUser',
                    method: 'POST',
                    data: usuarioPrueba,
                    contentType: 'application/x-www-form-urlencoded',
                    success: function(response) {
                        console.log('Respuesta del servidor:', response);
                        const result = typeof response === 'string' ? JSON.parse(response) : response;
                        if (result.success) {
                            window.location.reload();
                        } else {
                            console.error('Error del servidor:', result.message);
                            alert('Error: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la petición:', error);
                        alert('Error al crear el usuario: ' + error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener usuario aleatorio:', error);
                alert('Error al generar usuario de prueba: ' + error);
            }
        });
    }

    // Validación de contraseña
    function initializePasswordValidation() {
        // Patrones individuales para cada requisito
        const patterns = {
            minLength: /.{6,13}/,
            lowercase: /[a-z]/,
            uppercase: /[A-Z]/,
            number: /\d/,
            special: /[$@$!%*?&-.,]/,
            noSpaces: /^[^\s']+$/
        };

        function validatePassword(password) {
            return {
                minLength: patterns.minLength.test(password),
                lowercase: patterns.lowercase.test(password),
                uppercase: patterns.uppercase.test(password),
                number: patterns.number.test(password),
                special: patterns.special.test(password),
                noSpaces: patterns.noSpaces.test(password)
            };
        }

        function updatePasswordFeedback(results, messageContainer) {
            const messages = {
                minLength: 'Entre 6 y 13 caracteres',
                lowercase: 'Al menos una minúscula',
                uppercase: 'Al menos una mayúscula',
                number: 'Al menos un número',
                special: 'Al menos un carácter especial ($@!%*?&-.,)',
                noSpaces: 'Sin espacios ni comillas simples'
            };

            let html = '<ul class="text-sm mt-2">';
            for (const [requirement, passed] of Object.entries(results)) {
                const color = passed ? 'text-green-600' : 'text-red-600';
                const icon = passed ? '✓' : '✗';
                html += `<li class="${color}"><span class="mr-2">${icon}</span>${messages[requirement]}</li>`;
            }
            html += '</ul>';

            messageContainer.html(html);

            // Verificar si todos los requisitos se cumplen
            const allPassed = Object.values(results).every(result => result);
            return allPassed;
        }

        function handlePasswordValidation() {
            const password = $(this).val();
            const results = validatePassword(password);
            const messageContainer = $(this).siblings('.password-requirements');
            
            // Crear el contenedor de requisitos si no existe
            if (messageContainer.length === 0) {
                $(this).after('<div class="password-requirements"></div>');
            }
            
            const allPassed = updatePasswordFeedback(results, $(this).siblings('.password-requirements'));
            
            // Actualizar el estilo del input y el estado del botón
            if (allPassed) {
                $(this).css("border", "2px solid green");
                $("#btnEnviar").prop("disabled", false);
            } else {
                $(this).css("border", "2px solid red");
                $("#btnEnviar").prop("disabled", true);
            }
        }

        function handleEditPasswordValidation() {
            const password = $(this).val();
            const messageContainer = $(this).siblings('.password-requirements');
            
            // Crear el contenedor de requisitos si no existe
            if (messageContainer.length === 0) {
                $(this).after('<div class="password-requirements"></div>');
            }
            
            // Si el campo está vacío en modo edición
            if (password === "") {
                $(this).css("border", "");
                messageContainer.html("");
                $("button[type='submit']").prop("disabled", false);
                return;
            }
            
            const results = validatePassword(password);
            const allPassed = updatePasswordFeedback(results, messageContainer);
            
            // Actualizar el estilo del input y el estado del botón
            if (allPassed) {
                $(this).css("border", "2px solid green");
                $("button[type='submit']").prop("disabled", false);
            } else {
                $(this).css("border", "2px solid red");
                $("button[type='submit']").prop("disabled", true);
            }
        }

        // Asignar eventos
        $('#password').on('keyup', handlePasswordValidation);
        $('input[id^="edit-password-"]').on('keyup', handleEditPasswordValidation);
    }

    // Un único event listener para DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeTechnicians();
        initializeMaintenanceForm();
        initializeCharts();
        initializeUserManagement();
        initializePasswordValidation();
    });

    // Función para manejar el historial de mantenimiento
    function initMaintenanceHistory() {
        const form = document.getElementById('maintenance-form');
        const machineSelect = document.getElementById('machine-select');
        const historyContent = document.getElementById('history-content');
        const maintenanceHistory = document.getElementById('maintenance-history');
        const machineInfo = document.getElementById('machine-info');

        // Si no estamos en la página de mantenimiento, salir
        if (!form || !machineSelect || !historyContent || !maintenanceHistory) return;

        // Verificar si ya tiene un event listener para evitar duplicados
        const hasListener = form.getAttribute('data-has-maintenance-listener');
        if (hasListener) return;

        form.setAttribute('data-has-maintenance-listener', 'true');
        console.log('Inicializando historial de mantenimiento...');

        // Solo actualizar información de la máquina al cambiar selección
        machineSelect.addEventListener('change', async function() {
            const machineId = this.value;
            if (!machineId) {
                machineInfo.classList.add('hidden');
                maintenanceHistory.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`/api/machine/${machineId}`);
                if (!response.ok) throw new Error('Error al obtener información de la máquina');
                
                const data = await response.json();
                if (data && data.data) {
                    document.getElementById('info-nombre').textContent = data.data.name || 'No disponible';
                    document.getElementById('info-modelo').textContent = data.data.model || 'No disponible';
                    document.getElementById('info-fabricante').textContent = data.data.manufacturer || 'No disponible';
                    document.getElementById('info-ubicacion').textContent = data.data.location || 'No disponible';
                    machineInfo.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al obtener información de la máquina');
            }
        });

        // Mostrar historial solo cuando se hace clic en el botón
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const machineId = machineSelect.value;
            console.log('Consultando historial de mantenimiento para máquina:', machineId);
            
            if (!machineId) {
                alert('Por favor, selecciona una máquina');
                return;
            }

            try {
                historyContent.innerHTML = '<p class="text-gray-600">Cargando historial...</p>';
                const response = await fetch(`/api/maintenance/history/${machineId}`);
                console.log('Respuesta del servidor:', response);
                
                if (!response.ok) throw new Error('Error al obtener el historial');
                
                const history = await response.json();
                console.log('Historial recibido:', history);
                
                if (history && history.length > 0) {
                    historyContent.innerHTML = '';
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
                historyContent.innerHTML = `
                    <div class="bg-red-50 p-4 rounded-lg text-center">
                        <p class="text-red-600">Error al obtener el historial: ${error.message}</p>
                    </div>
                `;
                maintenanceHistory.classList.remove('hidden');
            }
        });
    }

    // Función para manejar el historial de incidencias
    function initIncidentHistory() {
        const machineSelect = document.getElementById('machine-select');
        const historyContent = document.getElementById('history-content');
        
        // Si no estamos en la página de incidencias, salir
        // Verificamos si estamos en la página de incidencias buscando un elemento específico del mantenimiento
        const isMaintenancePage = document.getElementById('maintenance-form') !== null;
        if (!machineSelect || !historyContent || isMaintenancePage) return;

        // Verificar si ya tiene un event listener para evitar duplicados
        const hasListener = machineSelect.getAttribute('data-has-incident-listener');
        if (hasListener) return;

        machineSelect.setAttribute('data-has-incident-listener', 'true');
        console.log('Inicializando historial de incidencias...');

        machineSelect.addEventListener('change', async function() {
            const machineId = this.value;
            console.log('Máquina seleccionada para incidencias:', machineId);

            if (!machineId) {
                historyContent.innerHTML = '<p class="text-gray-600">Selecciona una máquina para ver su historial.</p>';
                return;
            }

            try {
                historyContent.innerHTML = '<p class="text-gray-600">Cargando historial...</p>';
                const response = await fetch(`/history/incidents/${machineId}`);
                console.log('Respuesta del servidor:', response);
                
                if (!response.ok) {
                    throw new Error(`Error al obtener el historial: ${response.status} ${response.statusText}`);
                }
                
                const historial = await response.json();
                console.log('Datos del historial:', historial);
                
                if (historial && historial.length > 0) {
                    let html = `
                        <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                                    <th class="py-3 px-4 text-left border-b">Fecha</th>
                                    <th class="py-3 px-4 text-left border-b">Prioridad</th>
                                    <th class="py-3 px-4 text-left border-b">Descripción</th>
                                    <th class="py-3 px-4 text-left border-b">Estado</th>
                                    <th class="py-3 px-4 text-left border-b">Técnicos</th>
                                    <th class="py-3 px-4 text-left border-b">Tiempo</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    historial.forEach(registro => {
                        const prioridadClass = registro.tipo === 'high' ? 'bg-red-100 text-red-800' : 
                                             (registro.tipo === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                                             'bg-green-100 text-green-800');
                        
                        const estadoClass = registro.reparacion === 'resolved' ? 'bg-green-100 text-green-800' : 
                                          (registro.reparacion === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                          'bg-red-100 text-red-800');

                        const prioridadTexto = registro.tipo === 'high' ? 'Alta' : 
                                             (registro.tipo === 'medium' ? 'Media' : 'Baja');

                        const estadoTexto = registro.reparacion === 'resolved' ? 'Resuelto' : 
                                          (registro.reparacion === 'in_progress' ? 'En Progreso' : 'Pendiente');

                        html += `
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">${registro.fecha}</td>
                                <td class="py-2 px-4 border-b">
                                    <span class="px-2 py-1 rounded-full text-sm ${prioridadClass}">
                                        ${prioridadTexto}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b">${registro.fallo}</td>
                                <td class="py-2 px-4 border-b">
                                    <span class="px-2 py-1 rounded-full text-sm ${estadoClass}">
                                        ${estadoTexto}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b">${registro.tecnicos.join(", ")}</td>
                                <td class="py-2 px-4 border-b">${registro.tiempo}</td>
                            </tr>`;
                    });

                    html += `
                            </tbody>
                        </table>`;
                    
                    historyContent.innerHTML = html;
                } else {
                    historyContent.innerHTML = '<p class="text-gray-600">No hay registros de incidencias para esta máquina.</p>';
                }
            } catch (error) {
                console.error('Error:', error);
                historyContent.innerHTML = `<p class="text-red-600">Error al cargar el historial: ${error.message}</p>`;
            }
        });
    }

    // Función para hacer debounce de las búsquedas
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Función para realizar la búsqueda
    async function performSearch(query) {
        const searchResults = document.getElementById('searchResults');
        
        try {
            const response = await fetch(`/api/search?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success) {
                if (data.results.length === 0) {
                    searchResults.innerHTML = `
                        <div class="px-4 py-2 text-sm text-gray-500">
                            No se encontraron resultados
                        </div>`;
                } else {
                    searchResults.innerHTML = data.results.map(machine => `
                        <a href="/machinedetail/${machine.id}" class="block hover:bg-gray-50">
                            <div class="px-4 py-2 border-b">
                                <div class="text-sm font-medium text-gray-900">${machine.name}</div>
                                <div class="text-sm text-gray-500">
                                    ${machine.manufacturer} - ${machine.model}
                                    <br>
                                    Ubicación: ${machine.location}
                                </div>
                            </div>
                        </a>
                    `).join('');
                }
            } else {
                searchResults.innerHTML = `
                    <div class="px-4 py-2 text-sm text-red-500">
                        ${data.error}
                    </div>`;
            }
            
            searchResults.classList.remove('hidden');
            
        } catch (error) {
            console.error('Error:', error);
            searchResults.innerHTML = `
                <div class="px-4 py-2 text-sm text-red-500">
                    Error al realizar la búsqueda
                </div>`;
            searchResults.classList.remove('hidden');
        }
    }

    // Inicializar la búsqueda cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        if (searchInput && searchResults) {
            // Manejar la entrada de búsqueda con debounce
            searchInput.addEventListener('input', debounce(function(e) {
                const query = e.target.value.trim();
                
                if (query.length === 0) {
                    searchResults.innerHTML = '';
                    searchResults.classList.add('hidden');
                    return;
                }
                
                if (query.length < 3) {
                    searchResults.innerHTML = `
                        <div class="px-4 py-2 text-sm text-gray-500">
                            Escribe al menos 3 caracteres para buscar
                        </div>`;
                    searchResults.classList.remove('hidden');
                    return;
                }
                
                performSearch(query);
            }, 300));
            
            // Ocultar resultados cuando se hace clic fuera
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });
            
            // Prevenir envío del formulario
            const form = searchInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                });
            }
        }
    });

    // Inicializar las funciones cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initMaintenanceHistory();
            initIncidentHistory();
        });
    } else {
        initMaintenanceHistory();
        initIncidentHistory();
    }

    $(document).ready(function() {
        console.log('DOM cargado, configurando event listeners...');
    
        $('#createTestTechnician').on('click', function() {
            generarUsuarioPrueba('technician');
        });
    
        $('#createTestSupervisor').on('click', function() {
            generarUsuarioPrueba('supervisor');
        });
    });
    
    function generarUsuarioPrueba(role) {
        console.log('Generando usuario de prueba para el rol:', role);
    
        $.ajax({
            url: 'https://randomuser.me/api/?nat=es&inc=email,name,login',
            dataType: 'json',
            success: function(data) {
                const user = data.results[0];
                const usuarioPrueba = {
                    nombre: user.name.first,
                    apellido: user.name.last,
                    email: user.email,
                    pass: 'Testing10.',
                    rol: role
                };
    
                // Enviar al servidor
                $.ajax({
                    url: '/createTestUser',
                    method: 'POST',
                    data: usuarioPrueba,
                    contentType: 'application/x-www-form-urlencoded',
                    success: function(response) {
                        console.log('Respuesta del servidor:', response);
                        const result = typeof response === 'string' ? JSON.parse(response) : response;
                        if (result.success) {
                            // En lugar de intentar insertar el usuario en el DOM, recargamos la página
                            window.location.reload();
                        } else {
                            console.error('Error del servidor:', result.message);
                            alert('Error: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la petición:', error);
                        alert('Error al crear el usuario: ' + error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener usuario aleatorio:', error);
                alert('Error al generar usuario de prueba: ' + error);
            }
        });
    } 

    

console.log("password.js loaded");
console.log("messi");

// La expresión regular para validar la contraseña 
//(debe contener al menos una minúscula, una mayúscula, un número, un carácter especial y tener una longitud de entre 6 y 13 caracteres)
var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&-.,])[A-Za-z\d$@$!%*?&-.,]{6,13}[^'\s]/;

// Función para manejar la validación de la contraseña en el formulario de creación
function handlePasswordValidation() {
    var password = $(this).val();
    console.log(password);
    
    if (pattern.test(password)) {
        $(this).css("border", "2px solid green");
        $("#mss").html("Contraseña válida").css("color", "green");
        $("#btnEnviar").prop("disabled", false);
    } else {
        $(this).css("border", "2px solid red");
        $("#mss").html("Contraseña inválida").css("color", "red");
        $("#btnEnviar").prop("disabled", true);
    }
}

// Función para manejar la validación de la contraseña en el formulario de edición
function handleEditPasswordValidation() {
    var password = $(this).val();
    console.log(password);
    
    // Si el campo está vacío, permitir el envío (password opcional en edición)
    if (password === "") {
        $(this).css("border", "");
        $("#edit-mss").html("").css("display", "none");
        $("button[type='submit']").prop("disabled", false);
        return;
    }
    
    if (pattern.test(password)) {
        $(this).css("border", "2px solid green");
        $("#edit-mss").html("Contraseña válida").css({
            "color": "green",
            "display": "block"
        });
        $("button[type='submit']").prop("disabled", false);
    } else {
        $(this).css("border", "2px solid red");
        $("#edit-mss").html("Contraseña inválida").css({
            "color": "red",
            "display": "block"
        });
        $("button[type='submit']").prop("disabled", true);
    }
}

// Asignar eventos
$('#password').on('keyup', handlePasswordValidation);
$('input[id^="edit-password-"]').on('keyup', handleEditPasswordValidation);


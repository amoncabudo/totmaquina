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
    
                new Sortable(disponibles, {
                    group: 'tecnicos',
                    animation: 150,
                    onSort: updateSelectedTechnicians
                });
    
                new Sortable(asignados, {
                    group: 'tecnicos',
                    animation: 150,
                    onSort: updateSelectedTechnicians
                });
    
                function updateSelectedTechnicians() {
                    const selected = Array.from(asignados.children).map(li => li.dataset.id);
                    selectedTechnicians.value = selected.join(',');
                }
            });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="/incidents/create"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                
                fetch('/incidents/create', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        const successMessage = document.createElement('div');
                        successMessage.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                        successMessage.innerHTML = '<span class="block sm:inline">Incidencia creada correctamente</span>';
                        form.parentNode.insertBefore(successMessage, form);
                        
                        // Limpiar el formulario
                        form.reset();
                        
                        // Actualizar los contadores
                        location.reload(); // Por ahora recargamos la página, pero podríamos actualizar solo los números
                    } else {
                        // Mostrar mensaje de error
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                        errorMessage.innerHTML = '<span class="block sm:inline">Error al crear la incidencia</span>';
                        form.parentNode.insertBefore(errorMessage, form);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });
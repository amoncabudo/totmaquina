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
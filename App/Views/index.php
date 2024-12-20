<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tot Màquina - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="/main.css">
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">

    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <main class="main-content">
        <!-- Carrusel de imágenes -->
        <div class="carousel-container">
            <div class="carousel-slide active">
                <img src="/Images/Slider1_Ordenador.webp" alt="Ordenador" data-fancybox="gallery">
            </div>
            <div class="carousel-slide">
                <img src="/Images/Slider2_Pasta.webp" alt="Pasta" data-fancybox="gallery">
            </div>
            <div class="carousel-slide">
                <img src="/Images/Slide3_Empresa.webp" alt="Empresa" data-fancybox="gallery">
            </div>

            <button class="carousel-button carousel-prev" onclick="moveSlide(-1)">❮</button>
            <button class="carousel-button carousel-next" onclick="moveSlide(1)">❯</button>
        </div>

        <!-- Sección TOT MÀQUINA -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8">
            <div class="bg-gray-700 rounded-lg p-8 mb-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <h1 class="text-3xl font-bold text-white mb-4">TOT MÀQUINA</h1>
                        <p class="text-gray-300 mb-4" style="background-color: #364050;">
                            Especialistas en mantenimiento y gestión de infraestructuras TI.
                            Ofrecemos soluciones integrales para equipos informáticos, servidores,
                            redes y sistemas, garantizando la continuidad y eficiencia de su
                            infraestructura tecnológica.
                        </p>
                        <div class="border-t border-gray-600 pt-4 mt-4">
                            <h2 class="text-xl font-semibold text-white mb-2">Nuestros Servicios</h2>
                            <ul class="text-gray-300 list-disc list-inside space-y-2" style="background-color: #364050;">
                                <li>Mantenimiento de equipos informáticos y servidores</li>
                                <li>Gestión y monitorización de redes</li>
                                <li>Soporte técnico para hardware y software</li>
                                <li>Diagnóstico y reparación de componentes</li>
                            </ul>
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                        <div class="bg-gray-800 p-6 rounded-lg">
                            <h3 class="text-xl font-semibold text-white mb-3">Áreas de Especialización</h3>
                            <ul class="text-gray-300 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Servidores y Centros de Datos
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Redes y Comunicaciones
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Equipos de Escritorio y Portátiles
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Componentes y Periféricos
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Servicios Destacados -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-blue-600 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Mantenimiento Preventivo</h3>
                    <p class="text-gray-600">Optimización regular de equipos y sistemas para prevenir fallos y maximizar rendimiento.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-blue-600 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Respuesta Rápida</h3>
                    <p class="text-gray-600">Servicio técnico inmediato para resolver incidencias y minimizar tiempo de inactividad.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-blue-600 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Seguridad y Confianza</h3>
                    <p class="text-gray-600">Protocolos estrictos y técnicos certificados para garantizar la integridad de sus equipos.</p>
                </div>
            </div>

            <!-- Sección de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">+5000</div>
                    <div class="text-gray-600">Equipos Gestionados</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">99.9%</div>
                    <div class="text-gray-600">Uptime Garantizado</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">15 min</div>
                    <div class="text-gray-600">Tiempo de Respuesta</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">24/7</div>
                    <div class="text-gray-600">Soporte Técnico</div>
                </div>
            </div>

            <!-- Sección de Contacto -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">¿Necesita soporte técnico?</h3>
                <p class="text-gray-600 mb-4">
                    Nuestro equipo de expertos está disponible 24/7 para resolver cualquier
                    incidencia con sus equipos informáticos y sistemas.
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-gray-600">+34 900 123 456</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-600">soporte@totmaquina.com</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . "/layouts/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="/js/main.js"></script>

</body>

</html>
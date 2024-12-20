<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error - TotMaquina</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-800 to-gray-900 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <!-- Contenedor principal con efecto glassmorphism -->
        <div class="max-w-4xl w-full bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 md:p-12 border border-white/20">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Columna izquierda: Ilustración -->
                <div class="text-center md:text-left">
                    <div class="flex justify-center md:justify-start">
                        <!-- Icono de engranaje grande -->
                        <svg class="w-32 h-32 text-yellow-300 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="mt-6 space-y-2">
                        <div class="h-2 w-24 bg-yellow-300/30 rounded mx-auto md:mx-0"></div>
                        <div class="h-2 w-32 bg-yellow-300/20 rounded mx-auto md:mx-0"></div>
                        <div class="h-2 w-20 bg-yellow-300/10 rounded mx-auto md:mx-0"></div>
                    </div>
                </div>

                <!-- Columna derecha: Contenido -->
                <div class="text-center md:text-left">
                    <h1 class="text-4xl font-bold text-white mb-4">
                        Error Técnico
                    </h1>

                    <?php if (isset($error) && $error != ""): ?>
                        <div class="p-4 mb-6 text-sm text-red-400 rounded-lg bg-red-900/50 border border-red-800" role="alert">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <p class="text-gray-300 mb-8">
                        Nuestro equipo de mantenimiento está trabajando en ello.
                        <br>
                        <span class="text-yellow-300 font-semibold">¡Que no parin les màquines!</span>
                    </p>

                    <!-- Botones con estilo industrial -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="/" class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-yellow-500/50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Inicio
                        </a>
                        <button onclick="history.back()" class="inline-flex items-center px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-gray-700/50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </button>
                    </div>

                    <!-- Información de soporte con estilo industrial -->
                    <div class="mt-8 p-4 rounded-lg bg-gray-800/50 border border-gray-700">
                        <div class="flex items-center gap-3 text-gray-300">
                            <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="font-mono">soporte@totmaquina.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    
</html>
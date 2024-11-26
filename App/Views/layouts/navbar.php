<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/css/main.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav id="navbar" class="fixed top-0 w-full bg-black shadow-lg z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo y menú izquierdo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8" src="/img/logo.png" alt="Logo">
                    </div>
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#" class="text-white hover:text-gray-300 px-3 py-2 text-sm font-medium transition-colors duration-200">DASHBOARD</a>
                        <a href="#" class="text-white hover:text-gray-300 px-3 py-2 text-sm font-medium transition-colors duration-200">GESTIÓN</a>
                        <a href="#" class="text-white hover:text-gray-300 px-3 py-2 text-sm font-medium transition-colors duration-200">MANTENIMIENTO</a>
                        <a href="#" class="text-white hover:text-gray-300 px-3 py-2 text-sm font-medium transition-colors duration-200">INCIDENCIAS</a>
                        <a href="#" class="text-white hover:text-gray-300 px-3 py-2 text-sm font-medium transition-colors duration-200">HISTORIAL</a>
                    </div>
                </div>
                
                <!-- Búsqueda y perfil -->
                <div class="flex items-center">
                    <div class="relative">
                        <input type="text" placeholder="Search" 
                               class="bg-gray-100/90 backdrop-blur-sm rounded-full px-4 py-1 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <button class="absolute right-2 top-1/2 transform -translate-y-1/2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Iconos de notificación y perfil -->
                    <div class="ml-4 flex items-center space-x-4">
                        <button class="text-white hover:text-gray-300 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        <img class="h-8 w-8 rounded-full ring-2 ring-gray-300" src="/img/profile.jpg" alt="Profile">
                        <a href="?r=tancar-sessio" class="text-red-500 hover:text-red-400 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Espaciador para compensar el navbar fijo -->
    <div class="h-16"></div>
</body>
</html> 
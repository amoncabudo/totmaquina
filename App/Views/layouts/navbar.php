<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de control de TOT Màquina">
    <title>TOT Màquina - Panel de Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link href="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/css/main.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav id="navbar" class="fixed top-0 w-full bg-black shadow-lg z-50 transition-all duration-300" role="navigation" aria-label="Menú principal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo y menú de escritorio -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/index" class="flex items-center">
                            <div class="bg-white p-1.5 rounded-full">
                                <img class="h-10 w-10" src="/Images/Imagen_2024-11-28_150432915-removebg-preview.png" alt="Logo de la aplicación">
                            </div>
                        </a>
                    </div>
                    <!-- Menú de navegación para pantallas medianas y grandes -->
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4" role="menubar">
                            <a href="index" class="text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium" role="menuitem">DASHBOARD</a>
                            
                            <!-- Menú desplegable GESTIÓN -->
                            <div class="relative group" role="menuitem">
                                <button class="text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center" 
                                        aria-haspopup="true" 
                                        aria-expanded="false">
                                    GESTIÓN
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="absolute left-0 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200" role="menu">
                                    <a href="userManagement" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Gestión de Usuarios</a>
                                    <a href="machineinv" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Inventario de Máquinas</a>
                                </div>
                            </div>
                            
                            <!-- Menú desplegable MANTENIMIENTO -->
                            <div class="relative group" role="menuitem">
                                <button class="text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center" 
                                        aria-haspopup="true" 
                                        aria-expanded="false">
                                    MANTENIMIENTO
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="absolute left-0 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200" role="menu">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registro de Mantenimiento</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Estadísticas de Mantenimiento</a>
                                </div>
                            </div>

                            <div class="relative group" role="menuitem">
                                <button class="text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center" 
                                        aria-haspopup="true" 
                                        aria-expanded="false">
                                    INCIDENCIAS
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="absolute left-0 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200" role="menu">
                                    <a href="/incidents" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registro de Incidencias</a>
                                </div>
                            </div>
                            
                            <!-- Menú desplegable HISTORIAL -->
                            <div class="relative group" role="menuitem">
                                <button class="text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center" 
                                        aria-haspopup="true" 
                                        aria-expanded="false">
                                    HISTORIAL
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="absolute left-0 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200" role="menu">
                                    <a href="maintenance" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mantenimiento</a>
                                    <a href="history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registros</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Búsqueda y perfil -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="relative">
                        <label for="search-input" class="sr-only">Buscar</label>
                        <input type="search" 
                               id="search-input"
                               placeholder="Buscar..." 
                               class="bg-white text-gray-800 rounded-full px-4 py-1 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <button class="absolute right-2 top-1/2 transform -translate-y-1/2" aria-label="Buscar">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    
                    <?php if (isset($_SESSION["logat"]) && $_SESSION["logat"]): ?>
                        <!-- Iconos de notificación y perfil para usuario logueado -->
                        <div class="flex items-center space-x-4">
                            <!-- Botón de notificaciones con dropdown -->
                            <button id="dropdownNotificationButton" 
                                    data-dropdown-toggle="dropdownNotification" 
                                    class="text-white hover:text-gray-300 transition-colors duration-200 relative" 
                                    type="button"
                                    aria-label="Notificaciones"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                <span class="sr-only">Ver notificaciones</span>
                                <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            <!-- Dropdown notificaciones -->
                            <div id="dropdownNotification" 
                                 class="z-20 hidden w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow-lg" 
                                 aria-labelledby="dropdownNotificationButton"
                                 role="menu">
                                <div class="block px-4 py-2 font-medium text-gray-700 rounded-t-lg bg-gray-50" role="menuitem">
                                    Notificaciones
                                </div>
                                <div class="divide-y divide-gray-100">
                                    <!-- Contenido de notificaciones -->
                                </div>
                                <a href="/notifications" class="block py-2 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-50 hover:bg-gray-100" role="menuitem">
                                    Ver todas las notificaciones
                                </a>
                            </div>

                            <!-- Botón de perfil con dropdown -->
                            <button id="dropdownUserButton" data-dropdown-toggle="dropdownUser" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" type="button">
                                <span class="sr-only">Abrir menú de usuario</span>
                                <?php if (isset($_SESSION["user"]["avatar"]) && $_SESSION["user"]["avatar"]): ?>
                                    <?php $avatarPath = basename($_SESSION["user"]["avatar"]); ?>
                                    <img class="w-8 h-8 rounded-full" src="<?= '/Images/' . $_SESSION["user"]["avatar"]; ?>" alt="Avatar de usuario">
                                <?php else: ?>
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                <?php endif; ?>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownUser" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <div class="px-4 py-3 text-sm text-gray-900">
                                    <div>Hola, <?= $_SESSION["user"]["name"] ?></div>
                                </div>
                             
                                    <ul class="py-2 text-sm text-gray-700">
                                        <li>
                                            <a href="/adminPanel" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                                </svg>
                                                Panel de Administración
                                            </a>
                                        </li>
                                    </ul>
                             
                                <ul class="py-2 text-sm text-gray-700">
                                    <li>
                                        <a href="/userconfig" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Configuración
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Botón de cerrar sesión -->
                            <a href="tancar-sessio" class="text-red-500 hover:text-red-400 transition-colors duration-200" aria-label="Cerrar sesión">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Botón de inicio de sesión para usuario no logueado -->
                        <a href="/login" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Iniciar Sesión
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Botón menú móvil -->
                <div class="md:hidden">
                    <button data-collapse-toggle="navbar-mobile" type="button" 
                            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" 
                            aria-controls="navbar-mobile" 
                            aria-expanded="false"
                            aria-label="Abrir menú principal">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú móvil -->
        <div class="hidden md:hidden" id="navbar-mobile">
            <div class="space-y-1 px-2 pb-3 pt-2 bg-black">
                <a href="index" class="text-white block rounded-lg px-3 py-2 text-base font-medium hover:bg-gray-700" role="menuitem">DASHBOARD</a>
                
                <!-- Botón desplegable GESTIÓN móvil -->
                <button id="dropdownGestionLink" data-dropdown-toggle="dropdownGestion" 
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-base font-medium text-white hover:bg-gray-700"
                        aria-expanded="false"
                        aria-haspopup="true">
                    GESTIÓN 
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <!-- Menú desplegable GESTIÓN móvil -->
                <div id="dropdownGestion" class="z-10 hidden w-full bg-gray-700 rounded-lg" role="menu" aria-labelledby="dropdownGestionLink">
                    <ul class="py-2 text-sm text-white">
                        <li>
                            <a href="userManagement" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Gestión de Usuarios</a>
                        </li>
                        <li>
                            <a href="machineinv" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Inventario de Máquinas</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Botón desplegable MANTENIMIENTO móvil -->
                <button id="dropdownMaintenanceLink" data-dropdown-toggle="dropdownMaintenance" 
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-base font-medium text-white hover:bg-gray-700"
                        aria-expanded="false"
                        aria-haspopup="true">
                    MANTENIMIENTO 
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <!-- Menú desplegable MANTENIMIENTO móvil -->
                <div id="dropdownMaintenance" class="z-10 hidden w-full bg-gray-700 rounded-lg" role="menu" aria-labelledby="dropdownMaintenanceLink">
                    <ul class="py-2 text-sm text-white">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Registro de incidencias</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Estadísticas de incidencias</a>
                        </li>
                    </ul>
                </div>

                <!-- Botón desplegable INCIDENCIAS móvil -->
                <button id="dropdownIncidenciasLink" data-dropdown-toggle="dropdownIncidencias" 
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-base font-medium text-white hover:bg-gray-700"
                        aria-expanded="false"
                        aria-haspopup="true">
                    INCIDENCIAS 
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <!-- Menú desplegable INCIDENCIAS móvil -->
                <div id="dropdownIncidencias" class="z-10 hidden w-full bg-gray-700 rounded-lg" role="menu" aria-labelledby="dropdownIncidenciasLink">
                    <ul class="py-2 text-sm text-white">
                        <li>
                            <a href="incidents" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Gestión de Incidencias</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Botón desplegable HISTORIAL móvil -->
                <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" 
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-base font-medium text-white hover:bg-gray-700"
                        aria-expanded="false"
                        aria-haspopup="true">
                    HISTORIAL 
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <!-- Menú desplegable HISTORIAL móvil -->
                <div id="dropdownNavbar" class="z-10 hidden w-full bg-gray-700 rounded-lg" role="menu" aria-labelledby="dropdownNavbarLink">
                    <ul class="py-2 text-sm text-white">
                        <li>
                            <a href="maintenance" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Mantenimiento</a>
                        </li>
                        <li>
                            <a href="history" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Incidencias</a>
                        </li>
                    </ul>
                </div>

                <!-- Barra de búsqueda -->
                <div class="px-3 py-2">
                    <div class="relative">
                        <label for="mobile-search" class="sr-only">Buscar</label>
                        <input type="search" 
                               id="mobile-search"
                               placeholder="Buscar..." 
                               class="w-full bg-white text-gray-800 rounded-lg px-4 py-1 pl-10 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción móvil -->
                <div class="flex items-center justify-around py-4 mt-2 border-t border-gray-700">
                    <?php if (isset($_SESSION["logat"]) && $_SESSION["logat"]): ?>
                        <!-- Botón de notificaciones -->
                        <button id="dropdownNotificationButtonMobile" 
                                data-dropdown-toggle="dropdownNotificationMobile" 
                                class="inline-flex items-center justify-center p-2 text-white hover:bg-gray-700 rounded-lg transition-colors duration-200" 
                                type="button">
                            <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Dropdown notificaciones móvil -->
                        <div id="dropdownNotificationMobile" 
                             class="z-20 hidden w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow-lg" 
                             aria-labelledby="dropdownNotificationButtonMobile"
                             role="menu">
                            <div class="block px-4 py-2 font-medium text-gray-700 rounded-t-lg bg-gray-50" role="menuitem">
                                Notificaciones
                            </div>
                            <div class="divide-y divide-gray-100">
                                <a href="#" class="flex px-4 py-3 hover:bg-gray-100" role="menuitem">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-full pl-3">
                                        <div class="text-gray-500 text-sm mb-1.5">Nueva incidencia registrada</div>
                                        <div class="text-xs text-blue-600">Hace 10 minutos</div>
                                    </div>
                                </a>
                                <a href="#" class="flex px-4 py-3 hover:bg-gray-100" role="menuitem">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-green-100 rounded-lg">
                                            <svg class="w-4 h-4 text-green-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-full pl-3">
                                        <div class="text-gray-500 text-sm mb-1.5">Mantenimiento completado</div>
                                        <div class="text-xs text-green-600">Hace 2 horas</div>
                                    </div>
                                </a>
                            </div>
                            <a href="/notifications" class="block py-2 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-50 hover:bg-gray-100" role="menuitem">
                                Ver todas las notificaciones
                            </a>
                        </div>

                        <!-- Botón de perfil móvil -->
                        <button id="dropdownProfileButtonMobile" 
                                data-dropdown-toggle="dropdownProfileMobile"
                                class="inline-flex items-center justify-center p-2 text-white hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>

                        <!-- Dropdown perfil móvil -->
                        <div id="dropdownProfileMobile" 
                             class="z-20 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-lg">
                            <div class="px-4 py-3 text-sm text-gray-900">
                                <div>Hola, <?= $_SESSION["user"]["name"] ?></div>
                            </div>
                            <ul class="py-2 text-sm text-gray-700">
                                                            </li>
                                                                <li>
                                    <a href="/adminPanel" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Panel de Administración
                                    </a>
                                </li>
                                <li>
                                    <a href="/userconfig" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Configuración
                                    </a>

                            </ul>
                        </div>
                        <!-- Botón de cerrar sesión móvil -->
                        <a href="tancar-sessio" class="inline-flex items-center justify-center p-2 text-red-500 hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <!-- Botón de inicio de sesión móvil -->
                        <a href="/login" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Iniciar Sesión
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Espaciador para compensar el navbar fijo -->
    <div class="h-16" aria-hidden="true"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <?php include "cookie-banner.php"; ?>
</body>
</html> 
    <nav id="navbar" class="bg-black shadow-lg transition-all duration-300" role="navigation" aria-label="Menú principal">
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
                            
                            <!-- Menú desplegable GESTIÓN (accesible para todos) -->
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
                                    <?php if (isset($_SESSION["logat"]) && $_SESSION["logat"] && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'administrator'): ?>
                                        <a href="/userManagement" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Gestión de Usuarios</a>
                                    <?php endif; ?>
                                    <a href="/machineinv" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Inventario de Máquinas</a>
                                </div>
                            </div>
                            
                            <?php if (isset($_SESSION["logat"]) && $_SESSION["logat"]): ?>
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
                                        <a href="/maintenance" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registro de Mantenimiento</a>
                                        <a href="/maintenance/stats" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Estadísticas de Mantenimiento</a>
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
                                
                                <!-- Menú desplegable HISTORIAL (para administradores y supervisores) -->
                                <?php if (isset($_SESSION["user"]["role"]) && in_array($_SESSION["user"]["role"], ['administrator', 'supervisor'])): ?>
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
                                        <a href="/maintenance_history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mantenimiento</a>
                                        <a href="/history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registros</a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Búsqueda y perfil -->
                <div class="hidden md:flex items-center space-x-6">
                    <?php if (isset($_SESSION["logat"]) && $_SESSION["logat"]): ?>
                    <div class="relative">
                        <label for="searchInput" class="sr-only">Buscar máquinas</label>
                        <input type="search" 
                               id="searchInput"
                               placeholder="Buscar máquinas..." 
                               class="w-64 bg-white text-gray-800 rounded-full px-4 py-1 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400"
                               autocomplete="off">
                        <button class="absolute right-2 top-1/2 transform -translate-y-1/2" aria-label="Buscar">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <!-- Contenedor de resultados -->
                        <div id="searchResults" class="absolute left-0 right-0 mt-2 bg-white rounded-lg shadow-lg overflow-hidden hidden z-50">
                            <!-- Los resultados se insertarán aquí dinámicamente -->
                        </div>
                    </div>
                    
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
                                    <img class="w-8 h-8 rounded-full object-cover" src="/Images/<?= htmlspecialchars($_SESSION["user"]["avatar"]); ?>" alt="Foto de perfil">
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            <?= isset($_SESSION["user"]["name"]) ? strtoupper(substr($_SESSION["user"]["name"], 0, 1)) : 'U' ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownUser" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <div class="px-4 py-3 text-sm text-gray-900">
                                    <div class="font-medium">Hola, <?= htmlspecialchars($_SESSION["user"]["name"]) ?></div>
                                    <div class="text-xs text-gray-500 truncate"><?= htmlspecialchars($_SESSION["user"]["email"]) ?></div>
                                </div>
                                <ul class="py-2 text-sm text-gray-700">
                                    <?php if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'administrator'): ?>
                                    <!-- Panel de Administración (solo para administradores) -->
                                    <li>
                                        <a href="/adminPanel" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                            </svg>
                                            Panel de Administración
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <!-- Configuración -->
                                    <li>
                                        <a href="/userconfig" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.94 1.543.826 3.31 2.37 2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Configuración
                                        </a>
                                    </li>
                                    <!-- Mis Máquinas -->
                                    <li>
                                        <a href="/usermachines" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h4m-2-2v4" />
                                            </svg>
                                            Mis Máquinas
                                        </a>
                                    </li>
                                    <!-- Técnicos Asignados (solo para administradores y supervisores) -->
                                    <?php if (isset($_SESSION["user"]["role"]) && in_array($_SESSION["user"]["role"], ['administrator', 'supervisor'])): ?>
                                    <li>
                                        <a href="/assigned-technicians" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Técnicos Asignados
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                                <!-- Cerrar sesión -->
                                <div class="py-2">
                                    <a href="/tancar-sessio" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Cerrar sesión
                                    </a>
                                </div>
                            </div>

                            <!-- Botón de cerrar sesión -->
                            <a href="/tancar-sessio" class="text-red-500 hover:text-red-400 transition-colors duration-200" aria-label="Cerrar sesión">
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
                
                <?php if (isset($_SESSION["logat"]) && $_SESSION["logat"]): ?>
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
                            <?php if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'administrator'): ?>
                            <li>
                                <a href="/userManagement" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Gestión de Usuarios</a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a href="/machineinv" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Inventario de Máquinas</a>
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
                                <a href="/maintenance" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Registro de Mantenimiento</a>
                            </li>
                            <li>
                                <a href="/maintenance/stats" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Estadísticas de Mantenimiento</a>
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
                                <a href="/incidents" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Gestión de Incidencias</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Botón desplegable HISTORIAL móvil (para administradores y supervisores) -->
                    <?php if (isset($_SESSION["user"]["role"]) && in_array($_SESSION["user"]["role"], ['administrator', 'supervisor'])): ?>
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
                                <a href="/maintenance_history" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Mantenimiento</a>
                            </li>
                            <li>
                                <a href="/history" class="block px-4 py-2 hover:bg-gray-600" role="menuitem">Incidencias</a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Espaciador para compensar el navbar fijo -->
    <div class="h-16" aria-hidden="true"></div>

    <?php include "cookie-banner.php"; ?>
    
    <script src="/js/flowbite.min.js"></script>
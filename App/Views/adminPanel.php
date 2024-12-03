<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminPanel</title>
    <!-- TailwindCSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="flex justify-center mt-12">
        <div class="w-full max-w-6xl p-4">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Panel de Administración</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Tarjeta Añadir Máquina -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all cursor-pointer"
                    onclick="window.location.href='/machineinv'">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-plus-circle text-4xl text-blue-600 mr-4"></i>
                        <h5 class="text-xl font-bold text-gray-900">Añadir Máquina</h5>
                    </div>
                    <p class="text-gray-700">Gestiona y añade nuevas máquinas al sistema.</p>
                </div>

                <!-- Tarjeta Mantenimiento -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all cursor-pointer"
                    onclick="window.location.href='/maintenance'">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-tools text-4xl text-green-600 mr-4"></i>
                        <h5 class="text-xl font-bold text-gray-900">Mantenimiento</h5>
                    </div>
                    <p class="text-gray-700">Supervisa y gestiona el mantenimiento de las máquinas.</p>
                </div>

                <!-- Tarjeta Incidencias -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all cursor-pointer"
                    onclick="window.location.href='/incidents'">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-exclamation-triangle text-4xl text-yellow-600 mr-4"></i>
                        <h5 class="text-xl font-bold text-gray-900">Incidencias</h5>
                    </div>
                    <p class="text-gray-700">Revisa y gestiona las incidencias reportadas.</p>
                </div>

                <!-- Tarjeta Notificaciones -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all cursor-pointer"
                    onclick="window.location.href='/notifications'">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-bell text-4xl text-purple-600 mr-4"></i>
                        <h5 class="text-xl font-bold text-gray-900">Notificaciones</h5>
                    </div>
                    <p class="text-gray-700">Gestiona las notificaciones del sistema.</p>
                </div>

                <!-- Tarjeta Gestión de Usuarios -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all cursor-pointer"
                    onclick="window.location.href='/userManagement'">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-users text-4xl text-red-600 mr-4"></i>
                        <h5 class="text-xl font-bold text-gray-900">Gestión de Usuarios</h5>
                    </div>
                    <p class="text-gray-700">Administra los usuarios y sus permisos.</p>
                </div>

                <!-- Tarjeta Historial-->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all cursor-pointer"
                    onclick="window.location.href='/history'">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-history text-4xl text-indigo-600 mr-4"></i>
                        <h5 class="text-xl font-bold text-gray-900">Historial</h5>
                    </div>
                    <p class="text-gray-700">Visualiza el historial de mantenimiento.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

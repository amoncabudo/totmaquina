<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - TOT Màquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">

</head>

<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="container mx-auto px-4 pt-24 pb-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Panel de Administración</h1>
            <p class="text-gray-600">
                Bienvenido al panel de administración. Aquí podrás gestionar todos los aspectos del sistema.
            </p>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $error_message ?></span>
            </div>
        <?php endif; ?>

        <!-- Estado del Sistema -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Estado del Sistema</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-full">
                            <i class="fas fa-database text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">Base de Datos</p>
                            <p class="font-semibold text-green-600">
                                <?= isset($stats['system_status']['database']) && $stats['system_status']['database'] ? 'Conectada' : 'Desconectada' ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-full">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">Último Backup</p>
                            <p class="font-semibold text-blue-600">
                                <?= isset($stats['system_status']['last_backup']) ? $stats['system_status']['last_backup'] : 'No disponible' ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-full">
                            <i class="fas fa-hdd text-yellow-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">Uso de Disco</p>
                            <p class="font-semibold" style="color: #050400;">
                                <?= isset($stats['system_status']['disk_usage']) ? $stats['system_status']['disk_usage'] : 'No disponible' ?>
                            </p>
                        </div>

                    </div>
                </div>
                <div class="p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-full">
                            <i class="fas fa-server text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">Carga del Servidor</p>
                            <p class="font-semibold text-purple-600">
                                <?= isset($stats['system_status']['server_load']) ? $stats['system_status']['server_load'] : 'No disponible' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Usuarios -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Usuarios</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_users'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Máquinas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-cogs text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Máquinas</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_machines'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Incidencias -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Incidencias</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_incidents'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Actividad Reciente</h2>
            <div class="overflow-x-auto">
                <?php if (isset($stats['recent_activity']) && !empty($stats['recent_activity'])): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($stats['recent_activity'] as $activity): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $activity['type'] === 'incident' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                                            <?= ucfirst($activity['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($activity['detail']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php
                                            switch ($activity['status']) {
                                                case 'pending':
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                    break;
                                                case 'in_progress':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'completed':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst(str_replace('_', ' ', $activity['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= isset($activity['date']) ? date('d/m/Y H:i', strtotime($activity['date'])) : 'N/A' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No hay actividad reciente para mostrar</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Máquinas con más incidencias -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Máquinas con más Incidencias</h2>
            <?php if (isset($stats['machines_with_most_incidents']) && !empty($stats['machines_with_most_incidents'])): ?>
                <div class="flex flex-nowrap overflow-x-auto gap-4 pb-2">
                    <?php foreach ($stats['machines_with_most_incidents'] as $machine): ?>
                        <div class="flex-none w-48 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 truncate" title="<?= htmlspecialchars($machine['name']) ?>">
                                <?= htmlspecialchars($machine['name']) ?>
                            </h3>
                            <div class="mt-2">
                                <p class="text-2xl font-bold text-red-600"><?= $machine['incident_count'] ?></p>
                                <p class="text-sm text-gray-500">incidencias</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center">No hay datos de incidencias disponibles</p>
            <?php endif; ?>
        </div>

        <!-- Accesos Directos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

            <!-- Tarjeta Historial -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all cursor-pointer"
                onclick="window.location.href='/history'">
                <div class="flex items-center mb-4">
                    <i class="fas fa-history text-4xl text-indigo-600 mr-4"></i>
                    <h5 class="text-xl font-bold text-gray-900">Historial</h5>
                </div>
                <p class="text-gray-700">Visualiza el historial de mantenimiento.</p>
            </div>
        </div>

        <!-- Estadísticas Detalladas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Usuarios por Rol -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Usuarios por Rol</h3>
                <div class="space-y-4">
                    <?php if (isset($stats['users_by_role'])): ?>
                        <?php foreach ($stats['users_by_role'] as $roleData): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 capitalize"><?= $roleData['role'] ?></span>
                                <span class="text-gray-900 font-semibold"><?= $roleData['count'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Incidencias por Estado -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Incidencias por Estado</h3>
                <div class="space-y-4">
                    <?php if (isset($stats['incidents_by_status'])): ?>
                        <?php foreach ($stats['incidents_by_status'] as $statusData): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 capitalize"><?= $statusData['status'] ?></span>
                                <span class="text-gray-900 font-semibold"><?= $statusData['count'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/flowbite.min.js"></script>
    <scipt src="/js/bundle.js"></script>
    <script src="/js/main.js"></script>
</body>

</html>
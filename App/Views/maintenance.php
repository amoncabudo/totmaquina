<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de mantenimientos preventivos y correctivos">
    <title>Registro de Mantenimientos - Panel de Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <main id="main-content" class="container mx-auto px-4 py-8" role="main">
        <!-- Título y descripción -->
        <div class="text-center mb-8" role="banner">
            <h1 class="text-4xl font-bold text-gray-900 mb-4" tabindex="0">Registro de Mantenimientos</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto" tabindex="0">
                Sistema de gestión y seguimiento de mantenimientos preventivos y correctivos. Permite programar, asignar y realizar seguimiento de las tareas de mantenimiento.
            </p>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $success_message ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $error_message ?></span>
            </div>
        <?php endif; ?>

        <!-- Estado de mantenimientos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Pendientes -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-yellow-800 mb-2">Pendientes</h2>
                <div class="text-center">
                    <span class="text-4xl font-bold text-yellow-800">
                        <?php 
                        $pendientes = array_filter($maintenances, function($m) { 
                            return $m['status'] === 'pending'; 
                        });
                        echo count($pendientes);
                        ?>
                    </span>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-blue-800 mb-2">En Proceso</h2>
                <div class="text-center">
                    <span class="text-4xl font-bold text-blue-800">
                        <?php 
                        $enProceso = array_filter($maintenances, function($m) { 
                            return $m['status'] === 'in progress'; 
                        });
                        echo count($enProceso);
                        ?>
                    </span>
                </div>
            </div>

            <!-- Completados -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-green-800 mb-2">Completados</h2>
                <div class="text-center">
                    <span class="text-4xl font-bold text-green-800">
                        <?php 
                        $completados = array_filter($maintenances, function($m) { 
                            return $m['status'] === 'completed'; 
                        });
                        echo count($completados);
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulario de registro -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Registrar Nuevo Mantenimiento</h2>
            <form action="/maintenance/create" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Máquina -->
                    <div>
                        <label for="machine_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Máquina <span class="text-red-600">*</span>
                        </label>
                        <select id="machine_id" name="machine_id" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Seleccione una máquina</option>
                            <?php foreach ($machines as $machine): ?>
                                <option value="<?= $machine['id'] ?>"><?= htmlspecialchars($machine['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fecha programada -->
                    <div>
                        <label for="scheduled_date" class="block mb-2 text-sm font-medium text-gray-900">
                            Fecha Programada <span class="text-red-600">*</span>
                        </label>
                        <input type="date" id="scheduled_date" name="scheduled_date" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <!-- Tipo de mantenimiento -->
                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900">
                            Tipo <span class="text-red-600">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Seleccione un tipo</option>
                            <option value="preventive">Preventivo</option>
                            <option value="corrective">Correctivo</option>
                        </select>
                    </div>

                    <!-- Frecuencia -->
                    <div>
                        <label for="frequency" class="block mb-2 text-sm font-medium text-gray-900">
                            Frecuencia <span class="text-red-600">*</span>
                        </label>
                        <select id="frequency" name="frequency" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Seleccione una frecuencia</option>
                            <option value="weekly">Semanal</option>
                            <option value="monthly">Mensual</option>
                            <option value="quarterly">Trimestral</option>
                            <option value="yearly">Anual</option>
                        </select>
                    </div>

                    <!-- Técnicos -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Asignar Técnicos</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Técnicos Disponibles</h3>
                                <ul id="tecnicos-disponibles" class="min-h-[100px] border-2 border-dashed border-gray-300 rounded-lg p-2">
                                    <?php foreach ($technicians as $technician): ?>
                                        <li class="bg-white p-2 mb-2 rounded shadow cursor-move" data-id="<?= $technician['id'] ?>">
                                            <?= htmlspecialchars($technician['name'] . ' ' . $technician['surname']) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Técnicos Asignados</h3>
                                <ul id="tecnicos-asignados" class="min-h-[100px] border-2 border-dashed border-gray-300 rounded-lg p-2">
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" name="technicians[]" id="selected-technicians">
                    </div>
                </div>

                <div class="flex justify-center space-x-4">
                    <button type="submit" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Registrar Mantenimiento
                    </button>
                    <button type="reset"
                            class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">
                        Limpiar Formulario
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="/js/main.js"></script>
    <script src="/js/flowbite.min.js"></script>
</body>
</html>

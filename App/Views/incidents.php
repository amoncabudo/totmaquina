<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de incidencias y averías para el seguimiento y control de problemas técnicos">
    <title>Gestión de Incidencias - Panel de Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">

</head>
<body class="bg-gray-50">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <main id="main-content" class="container mx-auto px-4 py-8" role="main">
        <!-- Título y descripción -->
        <div class="text-center mb-8" role="banner">
            <h1 class="text-4xl font-bold text-gray-900 mb-4" tabindex="0">Gestión de Incidencias</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto" tabindex="0">
                Sistema de gestión y seguimiento de incidencias y averías. Permite registrar, priorizar y asignar incidencias a técnicos,
                así como realizar un seguimiento completo del estado de cada caso.
            </p>
        </div>

        <?php
        // Mostrar mensajes de éxito o error de la sesión
        if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success_message']) ?></span>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error_message']) ?></span>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Tarjetas de estado -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" role="region" aria-label="Estados de incidencias">
            <!-- Pendientes -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6" role="region" aria-labelledby="pendientes-title">
                <h2 id="pendientes-title" class="text-lg font-semibold text-yellow-800 mb-2">Pendientes</h2>
                <div class="pendientes-list text-center" data-status="pendiente" role="list" aria-label="Número de incidencias pendientes">
                    <span class="text-4xl font-bold text-yellow-800">
                        <?php 
                        $pendientes = array_filter($incidents, function($inc) { 
                            return $inc['status'] === 'pending'; 
                        });
                        echo count($pendientes);
                        ?>
                    </span>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6" role="region" aria-labelledby="proceso-title">
                <h2 id="proceso-title" class="text-lg font-semibold text-blue-800 mb-2">En Proceso</h2>
                <div class="proceso-list text-center" data-status="proceso" role="list" aria-label="Número de incidencias en proceso">
                    <span class="text-4xl font-bold text-blue-800">
                        <?php 
                        $enProceso = array_filter($incidents, function($inc) { 
                            return $inc['status'] === 'in progress'; 
                        });
                        echo count($enProceso);
                        ?>
                    </span>
                </div>
            </div>

            <!-- Resueltas -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6" role="region" aria-labelledby="resueltas-title">
                <h2 id="resueltas-title" class="text-lg font-semibold text-green-800 mb-2">Resueltas</h2>
                <div class="resueltas-list text-center" data-status="resuelta" role="list" aria-label="Número de incidencias resueltas">
                    <span class="text-4xl font-bold text-green-800">
                        <?php 
                        $resueltas = array_filter($incidents, function($inc) { 
                            return $inc['status'] === 'resolved'; 
                        });
                        echo count($resueltas);
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8" role="form">
            <h2 id="form-title" class="text-2xl font-bold text-gray-900 mb-6 text-center">Registrar Nueva Incidencia</h2>
            <form action="/incidents/create" method="POST" class="space-y-6" aria-labelledby="form-title">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Máquina -->
                    <div>
                        <label for="machine_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Máquina <span class="text-red-600" aria-hidden="true">*</span>
                        </label>
                        <select id="machine_id" name="machine_id" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Seleccione una máquina</option>
                            <?php foreach ($machines as $machine): ?>
                                <option value="<?= $machine['id'] ?>"><?= htmlspecialchars($machine['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Prioridad -->
                    <div>
                        <label for="priority" class="block mb-2 text-sm font-medium text-gray-900">
                            Prioridad <span class="text-red-600" aria-hidden="true">*</span>
                        </label>
                        <select id="priority" name="priority" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Seleccione una prioridad</option>
                            <option value="low">Baja</option>
                            <option value="medium">Media</option>
                            <option value="high">Alta</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">
                            Descripción de la Avería <span class="text-red-600" aria-hidden="true">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- Técnico -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">
                            Asignar Técnico <span class="text-red-600" aria-hidden="true">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Lista de técnicos disponibles -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Técnicos Disponibles</h3>
                                <ul id="tecnicos-disponibles" class="min-h-[150px] border-2 border-dashed border-gray-300 rounded-lg p-2">
                                    <?php foreach ($technicians as $technician): ?>
                                        <li class="bg-white p-2 mb-2 rounded shadow cursor-move flex items-center justify-between" 
                                            data-id="<?= $technician['id'] ?>">
                                            <span><?= htmlspecialchars($technician['name'] . ' ' . $technician['surname']) ?></span>
                                            <i class="fas fa-grip-lines text-gray-400"></i>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- Lista de técnicos asignados -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Técnico Asignado</h3>
                                <ul id="tecnicos-asignados" class="min-h-[150px] border-2 border-dashed border-gray-300 rounded-lg p-2">
                                </ul>
                            </div>
                        </div>
                        <!-- Campo oculto para el técnico seleccionado -->
                        <input type="hidden" name="technicians" id="selected-technician">
                    </div>
                </div>

                <div class="flex justify-center space-x-4">
                    <button type="submit" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Registrar Incidencia
                    </button>
                    <button type="reset"
                            class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5"
                            onclick="resetTechnicians()">
                        Limpiar Formulario
                    </button>
                </div>
            </form>
        </div>

        <!-- Estadísticas -->
        <div class="bg-white rounded-lg shadow-lg p-6" role="region" aria-labelledby="stats-title">
            <h2 id="stats-title" class="text-2xl font-bold text-gray-900 mb-6 text-center">Estadísticas de Incidencias</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 id="device-chart-title" class="text-lg font-medium text-gray-900 mb-4">Por Dispositivos</h3>
                    <canvas id="deviceChart" role="img" aria-labelledby="device-chart-title"></canvas>
                </div>
                <div>
                    <h3 id="response-chart-title" class="text-lg font-medium text-gray-900 mb-4">Tiempo de Respuesta</h3>
                    <canvas id="responseChart" role="img" aria-labelledby="response-chart-title"></canvas>
                </div>
                <div>
                    <h3 id="monthly-chart-title" class="text-lg font-medium text-gray-900 mb-4">Cantidad Mensual</h3>
                    <canvas id="monthlyChart" role="img" aria-labelledby="monthly-chart-title"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const disponibles = document.getElementById('tecnicos-disponibles');
            const asignados = document.getElementById('tecnicos-asignados');
            const selectedTechnician = document.getElementById('selected-technician');

            // Función para actualizar el técnico seleccionado
            function updateSelectedTechnician() {
                const assignedTechnicians = asignados.children;
                if (assignedTechnicians.length > 0) {
                    // Solo tomamos el primer técnico asignado
                    selectedTechnician.value = assignedTechnicians[0].dataset.id;
                } else {
                    selectedTechnician.value = '';
                }
            }

            // Inicializar Sortable para la lista de disponibles
            new Sortable(disponibles, {
                group: {
                    name: 'tecnicos',
                    pull: 'clone',
                    put: true
                },
                animation: 150,
                sort: false
            });

            // Inicializar Sortable para la lista de asignados
            new Sortable(asignados, {
                group: {
                    name: 'tecnicos',
                    pull: true,
                    put: function() {
                        // Solo permitir un técnico en la lista de asignados
                        return this.el.children.length < 1;
                    }
                },
                animation: 150,
                sort: false,
                onAdd: function() {
                    // Si hay más de un elemento, remover los anteriores
                    while (asignados.children.length > 1) {
                        disponibles.appendChild(asignados.children[0]);
                    }
                    updateSelectedTechnician();
                },
                onRemove: updateSelectedTechnician
            });

            // Validación del formulario
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const machine = document.getElementById('machine_id').value;
                    const priority = document.getElementById('priority').value;
                    const description = document.getElementById('description').value;
                    const technician = selectedTechnician.value;

                    if (!machine || !priority || !description || !technician) {
                        e.preventDefault();
                        alert('Por favor, complete todos los campos obligatorios');
                    }
                });
            }
        });

        // Función para resetear los técnicos al limpiar el formulario
        function resetTechnicians() {
            const disponibles = document.getElementById('tecnicos-disponibles');
            const asignados = document.getElementById('tecnicos-asignados');
            const selectedTechnician = document.getElementById('selected-technician');

            // Mover todos los técnicos de vuelta a disponibles
            while (asignados.firstChild) {
                disponibles.appendChild(asignados.firstChild);
            }
            selectedTechnician.value = '';
        }
    </script>
</body>
</html>

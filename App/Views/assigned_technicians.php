<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnicos Asignados - TOT Màquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">
</head>
<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="container mx-auto px-4 pt-24 pb-8">
        <!-- Toast para mensajes -->
        <div id="toast-container" class="fixed top-5 right-5 z-50"></div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                <h2 class="text-lg font-semibold text-gray-900">Técnicos Asignados</h2>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Tarea</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Asignación</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (isset($assignments) && is_array($assignments)): ?>
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr class="hover:bg-gray-50" data-assignment-id="<?php echo $assignment['id']; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php if ($assignment['technician_name']): ?>
                                                <?php echo htmlspecialchars($assignment['technician_name']); ?>
                                            <?php else: ?>
                                                <span class="text-gray-400">Sin asignar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php if ($assignment['type'] == 'incident'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Incidencia</span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Mantenimiento</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($assignment['task_id']); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($assignment['description']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($assignment['assigned_date']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button type="button"
                                                    data-modal-target="changeTechnicianModal-<?php echo $assignment['id']; ?>" 
                                                    data-modal-toggle="changeTechnicianModal-<?php echo $assignment['id']; ?>"
                                                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                                                Cambiar Técnico
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal para cambiar técnico -->
                                    <div id="changeTechnicianModal-<?php echo $assignment['id']; ?>" 
                                         tabindex="-1" 
                                         aria-hidden="true" 
                                         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative w-full max-w-md max-h-full">
                                            <div class="relative bg-white rounded-lg shadow">
                                                <div class="flex items-center justify-between p-4 border-b rounded-t">
                                                    <h3 class="text-xl font-semibold text-gray-900">
                                                        Cambiar Técnico
                                                    </h3>
                                                    <button type="button" 
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                                                            data-modal-hide="changeTechnicianModal-<?php echo $assignment['id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                        <span class="sr-only">Cerrar modal</span>
                                                    </button>
                                                </div>
                                                <div class="p-6">
                                                    <form>
                                                        <div class="mb-4">
                                                            <label for="newTechnician-<?php echo $assignment['id']; ?>" class="block mb-2 text-sm font-medium text-gray-900">
                                                                Seleccionar Nuevo Técnico
                                                            </label>
                                                            <select id="newTechnician-<?php echo $assignment['id']; ?>" 
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                                <?php foreach ($technicians as $technician): ?>
                                                                    <option value="<?php echo $technician['id']; ?>" 
                                                                            <?php echo ($technician['id'] == $assignment['technician_id']) ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($technician['name']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="flex justify-end">
                                                            <button type="button"
                                                                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 focus:outline-none"
                                                                    onclick="saveTechnicianChange(<?php echo $assignment['id']; ?>)">
                                                                Guardar Cambios
                                                            </button>
                                                            <button type="button"
                                                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:outline-none"
                                                                    data-modal-hide="changeTechnicianModal-<?php echo $assignment['id']; ?>">
                                                                Cancelar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No hay asignaciones disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/bundle.js"></script>
    <script src="/js/flowbite.min.js"></script>
    <script src="/js/main.js"></script>
</body>
</html> 
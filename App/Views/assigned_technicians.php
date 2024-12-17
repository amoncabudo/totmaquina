<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnicos Asignados - TOT Màquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
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
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($assignment['technician_name']); ?></td>
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
                                                    data-modal-target="changeTechnicianModal-<?php echo $assignment['task_id']; ?>" 
                                                    data-modal-toggle="changeTechnicianModal-<?php echo $assignment['task_id']; ?>"
                                                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                                                Cambiar Técnico
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal para cambiar técnico -->
                                    <div id="changeTechnicianModal-<?php echo $assignment['task_id']; ?>" 
                                         data-type="<?php echo $assignment['type']; ?>"
                                         tabindex="-1" 
                                         aria-hidden="true" 
                                         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative w-full max-w-md max-h-full">
                                            <div class="relative bg-white rounded-lg shadow">
                                                <div class="flex items-center justify-between p-4 border-b rounded-t">
                                                    <h3 class="text-xl font-semibold text-gray-900">Cambiar Técnico Asignado</h3>
                                                    <button type="button" 
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" 
                                                            data-modal-hide="changeTechnicianModal-<?php echo $assignment['task_id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                        <span class="sr-only">Cerrar modal</span>
                                                    </button>
                                                </div>
                                                <div class="p-6">
                                                    <form id="changeTechnicianForm-<?php echo $assignment['task_id']; ?>" onsubmit="event.preventDefault();">
                                                        <div class="mb-4">
                                                            <label for="newTechnician-<?php echo $assignment['task_id']; ?>" class="block mb-2 text-sm font-medium text-gray-900">Seleccionar Nuevo Técnico</label>
                                                            <select id="newTechnician-<?php echo $assignment['task_id']; ?>" 
                                                                    name="newTechnician" 
                                                                    required 
                                                                    data-current-technician="<?php echo htmlspecialchars($assignment['technician_id']); ?>"
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                                <option value="">Seleccionar técnico</option>
                                                                <?php if (isset($technicians) && is_array($technicians)): ?>
                                                                    <?php foreach ($technicians as $technician): ?>
                                                                        <option value="<?php echo $technician['id']; ?>"
                                                                                <?php echo ($technician['id'] == $assignment['technician_id']) ? 'selected' : ''; ?>>
                                                                            <?php echo htmlspecialchars($technician['name']); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                            <div class="loading-spinner hidden mt-2">
                                                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-700"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b">
                                                            <button type="button" 
                                                                    class="mr-2 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10" 
                                                                    data-modal-hide="changeTechnicianModal-<?php echo $assignment['task_id']; ?>">
                                                                Cancelar
                                                            </button>
                                                            <button type="button" 
                                                                    onclick="saveTechnicianChange(<?php echo $assignment['task_id']; ?>)" 
                                                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                                Guardar
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script src="/js/main.js"></script>
    <script>
    function saveTechnicianChange(assignmentId) {
        console.log('Iniciando cambio de técnico...');
        console.log('ID de asignación:', assignmentId);

        // Obtener el select y el modal correspondientes
        const selectElement = document.getElementById(`newTechnician-${assignmentId}`);
        const modalElement = document.getElementById(`changeTechnicianModal-${assignmentId}`);
        
        if (!selectElement || !modalElement) {
            console.error('No se encontraron los elementos necesarios');
            return;
        }

        // Obtener el nuevo técnico seleccionado
        const newTechnicianId = selectElement.value;
        if (!newTechnicianId) {
            showNotification('Por favor, selecciona un técnico', 'error');
            return;
        }
        
        console.log('Nuevo técnico ID:', newTechnicianId);
        
        // Mostrar indicador de carga
        const loadingSpinner = selectElement.parentElement.querySelector('.loading-spinner');
        if (loadingSpinner) {
            loadingSpinner.classList.remove('hidden');
        }
        
        // Deshabilitar el select mientras se procesa
        selectElement.disabled = true;

        // Preparar los datos para enviar
        const data = {
            assignmentId: assignmentId,
            newTechnicianId: newTechnicianId,
            type: modalElement.dataset.type
        };
        console.log('Datos a enviar:', data);

        // Realizar la petición al servidor
        fetch('/api/change-technician', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(async response => {
            console.log('Respuesta del servidor:', response.status);
            const contentType = response.headers.get("content-type");
            console.log('Tipo de contenido:', contentType);

            if (!response.ok) {
                const text = await response.text();
                console.log('Respuesta de error completa:', text);
                try {
                    const json = JSON.parse(text);
                    throw new Error(json.message || 'Error al actualizar el técnico');
                } catch (e) {
                    throw new Error('Error en la respuesta del servidor: ' + text);
                }
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta exitosa:', data);
            if (data.success) {
                // Actualizar el valor actual del técnico en el select
                selectElement.dataset.currentTechnician = newTechnicianId;
                
                // Cerrar el modal
                const modal = document.getElementById(`changeTechnicianModal-${assignmentId}`);
                if (modal && typeof modal.hide === 'function') {
                    modal.hide();
                }
                
                showNotification('Técnico actualizado correctamente', 'success');
                
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Error al actualizar el técnico');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            showNotification(error.message || 'Error al actualizar el técnico', 'error');
            
            // Restaurar el valor anterior del select
            if (selectElement.dataset.currentTechnician) {
                selectElement.value = selectElement.dataset.currentTechnician;
            }
        })
        .finally(() => {
            // Ocultar indicador de carga
            if (loadingSpinner) {
                loadingSpinner.classList.add('hidden');
            }
            // Habilitar el select
            selectElement.disabled = false;
        });
    }

    // Función auxiliar para mostrar notificaciones
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 p-4 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } shadow-lg z-50`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Inicializar los selectores de técnicos
    document.addEventListener('DOMContentLoaded', function() {
        // Guardar el valor inicial de cada selector
        const selectors = document.querySelectorAll('select[id^="newTechnician-"]');
        selectors.forEach(select => {
            select.dataset.currentTechnician = select.value;
        });
    });
    </script>
</body>
</html> 
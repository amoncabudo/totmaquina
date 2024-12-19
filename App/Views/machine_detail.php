<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de la Máquina</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
  <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">
</head>
<?php include __DIR__ . "/layouts/navbar.php"; ?>

<body class="bg-gray-100 min-h-screen">
  <div class="max-w-3xl mx-auto p-8 mt-10 rounded-lg">
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Detalle de la Máquina</h1>
    <div class="bg-gray-200 p-4 rounded-lg shadow-md grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <h2 class="text-lg font-bold mb-6"><?php echo htmlspecialchars($machine['name']) ?></h2>
        <p class="mb-6"><strong>Modelo:</strong> <?php echo htmlspecialchars($machine['model']) ?></p>
        <p class="mb-6"><strong>Fabricante:</strong> <?php echo htmlspecialchars($machine['manufacturer']) ?></p>
        <p class="mb-6"><strong>Fecha de instalación:</strong> <?php echo htmlspecialchars($machine['installation_date']) ?></p>
        <p class="mb-6"><strong>Número de serie:</strong> <?php echo htmlspecialchars($machine['serial_number']) ?></p>
        <p class="mb-6"><strong>Ubicación:</strong> <?php echo htmlspecialchars($machine['location']) ?></p>
        <p class="mb-6"><strong>Coordenadas:</strong> <?php echo htmlspecialchars($machine['coordinates']) ?></p>
      </div>
      <div class="flex flex-col items-center">
        <img src="<?php echo htmlspecialchars('/Images/' . $machine['photo']); ?>" alt="Imagen de la máquina" class="w-full mb-4 shadow-md rounded-lg">
        <div class="bg-gray-50 p-4 rounded-lg">
          <div class="flex gap-4">
            <!-- Técnicos Disponibles -->
            <div class="bg-gray-50 p-4 rounded-lg w-1/2">
              <h3 class="text-sm font-medium text-gray-700 mb-2">Técnicos Disponibles</h3>
              <ul id="tecnicos-disponibles" class="min-h-[100px] border-2 border-dashed border-gray-300 rounded-lg p-2">
                <?php foreach ($technicians as $technician): ?>
                  <li class="bg-white p-2 mb-2 rounded shadow cursor-move" data-id="<?= $technician['id'] ?>">
                    <?= htmlspecialchars($technician['name'] . ' ' . $technician['surname']) ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>

            <!-- Técnicos Asignados -->
            <div class="bg-gray-50 p-4 rounded-lg w-1/2">
              <h3 class="text-sm font-medium text-gray-700 mb-2">Técnicos Asignados</h3>
              <ul id="tecnicos-asignados" class="min-h-[100px] border-2 border-dashed border-gray-300 rounded-lg p-2">
                <!-- Los técnicos asignados se mostrarán aquí -->
              </ul>
            </div>
          </div>

          <!-- Botón de Guardar -->
          <div class="mt-4 flex justify-end">
            <button id="save-technicians" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
              Guardar Asignación
            </button>
          </div>

          <input type="hidden" name="technicians_data" id="<?= $machine['technicians_data'] ?>">
          <input type="hidden" id="machine-id" value="<?= $machine['id'] ?>">
        </div>
      </div>
    </div>
    <div class="flex justify-end mt-4">
      <a href="javascript:void(0);" onclick="history.back();" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9h13a5 5 0 0 1 0 10H7M3 9l4-4M3 9l4 4"/>
        </svg>  
        <span class="sr-only">Volver</span>
      </a>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const disponibles = document.getElementById('tecnicos-disponibles');
      const asignados = document.getElementById('tecnicos-asignados');

      disponibles.querySelectorAll('li').forEach(li => {
        li.setAttribute('draggable', 'true');
        li.addEventListener('dragstart', event => {
          event.dataTransfer.setData('text/plain', li.dataset.id);
        });
      });

      [disponibles, asignados].forEach(zone => {
        zone.addEventListener('dragover', event => event.preventDefault());
        zone.addEventListener('drop', event => {
          event.preventDefault();
          const id = event.dataTransfer.getData('text/plain');
          const technician = document.querySelector(`[data-id="${id}"]`);
          zone.appendChild(technician);
        });
      });

      document.getElementById('save-technicians').addEventListener('click', () => {
        const assignedTechnicians = Array.from(asignados.querySelectorAll('li')).map(li => li.dataset.id);
        const machineId = document.getElementById('machine-id').value;

        fetch('/save-technicians', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ machine_id: machineId, technician_ids: assignedTechnicians })
        })
        .then(response => response.ok ? alert('Asignación guardada con éxito') : Promise.reject())
        .catch(() => alert('Error al guardar la asignación'));
      });
    });
  </script>
</body>
</html>

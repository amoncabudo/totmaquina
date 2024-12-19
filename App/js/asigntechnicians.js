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

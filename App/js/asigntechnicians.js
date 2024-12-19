// Habilitar "dragstart" en los técnicos disponibles
document.addEventListener("DOMContentLoaded", () => {
    const disponibles = document.getElementById('tecnicos-disponibles');
    const asignados = document.getElementById('tecnicos-asignados');

    if (!disponibles || !asignados) {
        console.error('No se encontraron las listas necesarias para el drag and drop');
        return;
    }

    // Hacer que los elementos sean "draggables"
    disponibles.querySelectorAll('li').forEach(li => {
        li.setAttribute('draggable', 'true');
        li.addEventListener('dragstart', dragStartHandler);
    });

    // Configurar las áreas "dropzone"
    [disponibles, asignados].forEach(zone => {
        zone.addEventListener('dragover', allowDropHandler);
        zone.addEventListener('drop', dropHandler);
    });
});

// Gestionar el evento dragstart
function dragStartHandler(event) {
    if (!event.target) return;
    const id = event.target.dataset.id;
    event.dataTransfer.setData('text/plain', id);
}

// Permitir el drop
function allowDropHandler(event) {
    event.preventDefault(); // Permite que el drop ocurra
}

// Gestionar el evento drop
function dropHandler(event) {
    event.preventDefault();

    const id = event.dataTransfer.getData('text/plain');
    const technicianElement = document.querySelector(`[data-id="${id}"]`);
    const dropZone = event.target.closest('ul');

    if (!dropZone || !technicianElement) return;

    // Mover el técnico al nuevo contenedor
    dropZone.appendChild(technicianElement);
}

// Guardar asignación al hacer clic en el botón
document.getElementById('save-technicians').addEventListener('click', () => {
    const assignedTechnicians = Array.from(document.querySelectorAll('#tecnicos-asignados li'))
        .map(li => li.dataset.id);
    const machineId = document.getElementById('machine-id').value;

    fetch('/save-technicians', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            machine_id: machineId,
            technician_ids: assignedTechnicians
        })
    })
    .then(response => {
        if (response.ok) {
            alert('Asignación guardada con éxito');
            window.location.reload();
        } else {
            throw new Error('Error al guardar la asignación');
        }
    })
    .catch(error => {
        console.error('Error al guardar la asignación:', error);
    });
});

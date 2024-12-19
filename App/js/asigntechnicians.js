window.allowDrop = function(ev) {
    if (!ev) return;
    ev.preventDefault();
}

window.drag = function(ev) {
    if (!ev || !ev.target) return;
    const draggableElement = ev.target.closest('.draggable');
    if (!draggableElement) return;

    const technicianId = draggableElement.dataset.technicianId;
    ev.dataTransfer.setData("technicianId", technicianId);
}

window.drop = function(ev) {
    if (!ev) return;
    ev.preventDefault();

    const droppableElement = ev.target.closest('.droppable');
    const technicianId = ev.dataTransfer.getData("technicianId");
    const machineId = droppableElement.dataset.machineId;
    const loadingSpan = droppableElement.querySelector('span');
    const originalText = loadingSpan.textContent;
    loadingSpan.textContent = 'Asignando...';

    fetch('/assign-technician', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            technician_id: technicianId,
            machine_id: machineId
        })
    })
    .then(response => {
        if (response.ok) {
            // Recarga la página después de una asignación exitosa
            window.location.reload();
        } else {
            // Si hay un error, restauramos el texto original
            loadingSpan.textContent = originalText;
            throw new Error('Error en la asignación');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        loadingSpan.textContent = originalText;
    });
}

// Inicialización del drag and drop de técnicos
function initTechnicianDragDrop() {
    const disponibles = document.getElementById('tecnicos-disponibles');
    const asignados = document.getElementById('tecnicos-asignados');
    
    console.log('Intentando inicializar drag and drop...');
    
    if (!disponibles || !asignados) {
        console.log('No se encontraron las listas necesarias para el drag and drop');
        return;
    }
}
window.allowDrop = function (ev) {
    if (!ev || !ev.target) return;
    const droppableElement = ev.target.closest('.droppable');
    if (droppableElement) {
        ev.preventDefault(); // Només permet el drop en elements que són droppables.
    }
};

window.drag = function (ev) {
    if (!ev || !ev.target) return;
    const draggableElement = ev.target.closest('.draggable');
    if (draggableElement && draggableElement.dataset.technicianId) {
        ev.dataTransfer.setData("technicianId", draggableElement.dataset.technicianId);
    } else {
        console.warn('Element draggable no vàlid o falta l\'atribut "data-technician-id".');
    }
};

window.drop = function (ev) {
    if (!ev) return;
    ev.preventDefault();

    const droppableElement = ev.target.closest('.droppable');
    if (!droppableElement) {
        console.warn('No es pot fer el drop perquè l\'element no és droppable.');
        return;
    }

    const technicianId = ev.dataTransfer.getData("technicianId");
    if (!technicianId) {
        console.warn('No s\'ha pogut obtenir l\'ID del tècnic des del dataTransfer.');
        return;
    }

    const machineId = droppableElement.dataset.machineId;
    const loadingSpan = droppableElement.querySelector('span');
    if (!loadingSpan) {
        console.warn('No s\'ha trobat cap element <span> dins del droppable.');
        return;
    }

    const originalText = loadingSpan.textContent;
    loadingSpan.textContent = 'Asignando...';

    fetch('/assign-technician', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            technician_id: technicianId,
            machine_id: machineId,
        }),
    })
        .then((response) => {
            if (response.ok) {
                window.location.reload();
            } else {
                loadingSpan.textContent = originalText;
                console.error('Error en la resposta del servidor.');
                throw new Error('Error en la assignació');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            loadingSpan.textContent = originalText;
        });
};

// Inicialització del drag-and-drop
function initTechnicianDragDrop() {
    const disponibles = document.getElementById('tecnicos-disponibles');
    const asignados = document.getElementById('tecnicos-asignados');

    console.log('Intentant inicialitzar el drag and drop...');

    if (!disponibles || !asignados) {
        console.warn('No s\'han trobat les llistes necessàries per al drag-and-drop.');
        return;
    }

    disponibles.querySelectorAll('.draggable').forEach((element) => {
        element.setAttribute('draggable', 'true');
        element.addEventListener('dragstart', window.drag);
    });

    asignados.querySelectorAll('.droppable').forEach((element) => {
        element.addEventListener('dragover', window.allowDrop);
        element.addEventListener('drop', window.drop);
    });

    console.log('Drag and drop inicialitzat correctament.');
}

// Crida inicial a la funció d'inicialització
initTechnicianDragDrop();

if (document.getElementById("asigntechnicians")) { //If the document has the id asigntechnicians, then run the code
document.addEventListener("DOMContentLoaded", () => { //When  the dom is loaded, run the code
    const disponibles = document.getElementById('tecnicos-disponibles'); //Get the element with the id tecnicos-disponibles
    const asignados = document.getElementById('tecnicos-asignados'); //Get the element with the id tecnicos-asignados

    if (!disponibles || !asignados) { //If the element with the id tecnicos-disponibles or tecnicos-asignados is not found, then return
        console.log('No se encontraron las listas necesarias para el drag and drop');
        return;
    }

    // Elements with the "draggable" attribute
    disponibles.querySelectorAll('li').forEach(li => { //For each element with the class li, set the attribute draggable to true and add an event listener to the dragstart event
        li.setAttribute('draggable', 'true'); //Set the attribute draggable to true
        li.addEventListener('dragstart', dragStartHandler); //Add an event listener to the dragstart event
    });

    // Drop zones
    [disponibles, asignados].forEach(zone => { //For each element with the class zone, add an event listener to the dragover event and the drop event
        zone.addEventListener('dragover', allowDropHandler); //Add an event listener to the dragover event
        zone.addEventListener('drop', dropHandler); //Add an event listener to the drop event
    });
});

// Drag start handler
function dragStartHandler(event) {
    if (!event.target) return; //If the event target is not found, then return
    const id = event.target.dataset.id; //Get the id of the event target
    event.dataTransfer.setData('text/plain', id); //Set the data transfer data to the id
}

// Allow drop handler
function allowDropHandler(event) {
    event.preventDefault(); //Prevent the default behavior of the event
}

// Drop handler
function dropHandler(event) { //When the event is dropped, run the code
    event.preventDefault(); //Prevent the default behavior of the event

    const id = event.dataTransfer.getData('text/plain'); //Get the id of the event
    const technicianElement = document.querySelector(`[data-id="${id}"]`); //Get the element with the data-id attribute equal to the id
    const dropZone = event.target.closest('ul'); //Get the closest element with the class ul

    if (!dropZone || !technicianElement) return; //If the drop zone or the technician element is not found, then return

    // Remove the technician from the previous list
    dropZone.appendChild(technicianElement); //Add the technician element to the drop zone  
}   

// Save technicians
document.getElementById('save-technicians').addEventListener('click', () => { //When the element with the id save-technicians is clicked, run the code
    const assignedTechnicians = Array.from(document.querySelectorAll('#tecnicos-asignados li')) //Get the elements with the class li and the id tecnicos-asignados
        .map(li => li.dataset.id); //Get the id of the elements with the class li and the id tecnicos-asignados
    const machineId = document.getElementById('machine-id').value; //Get the value of the element with the id machine-id

    fetch('/save-technicians', { //Send a post request to the url /save-technicians
        method: 'POST', //Set the method to post
        headers: { //Set the headers
            'Content-Type': 'application/json', //Set the content type to json
        },
        body: JSON.stringify({ //Set the body to the json string
            machine_id: machineId, //Set the machine_id to the machineId
            technician_ids: assignedTechnicians //Set the technician_ids to the assignedTechnicians
        })
    })
    .then(response => { //When the response is received, run the code
        if (response.ok) { //If the response is ok, run the code
            alert('Asignación guardada con éxito'); //Show an alert
            window.location.reload(); //Reload the page
        } else {
            throw new Error('Error al guardar la asignación'); //Throw an error
        }
    })
    .catch(error => { //When the error is caught, run the code
        console.error('Error al guardar la asignación:', error); //Show an error
    });
});
}
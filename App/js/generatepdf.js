import { jsPDF } from 'jspdf';//Import the jsPDF library

async function generateIncidentPDF(machineId) { //When the function generateIncidentPDF is called, run the code
    try {
        const response = await fetch(`/history/incidents/${machineId}`, { //Send a get request to the url /history/incidents/${machineId}
            method: 'GET', //Set the method to get
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' //Set the headers
            },
            credentials: 'same-origin' //Set the credentials to same-origin
        });

        if (!response.ok) { //If the response is not ok, throw an error
            throw new Error(`HTTP error! status: ${response.status}`); 
        }

        const data = await response.json(); //Get the data from the response
        if (!data.success) { //If the data is not successful, throw an error
            throw new Error(data.message || 'Error al cargar el historial'); 
        }

        const pdf = new jsPDF(); //Create a new jsPDF object
        const marginTop = 10;
        const marginLeft = 10;
        const marginRight = 10;
        const marginBottom = 10;
        
        pdf.setFontSize(24); 
        pdf.setTextColor(51, 150, 255); 
        pdf.setFont('helvetica', 'bold'); 
        pdf.text(`Historial de Incidencias - ${data.machine.name}`, marginLeft, marginTop); 
        
        pdf.setFontSize(16);
        pdf.setTextColor(51, 150, 255);
        pdf.setFont('helvetica', 'bold');
        pdf.text(`Información de la máquina`, 10, 20);
        pdf.setFont('helvetica', 'normal');
        pdf.setTextColor(0, 0, 0);

        pdf.text(`Modelo: ${data.machine.model}`, 15, 30);
        pdf.text(`Fabricante: ${data.machine.manufacturer}`, 15, 35);
        pdf.text(`Ubicación: ${data.machine.location}`, 15, 40);

        let yOffset = 50; //Set the yOffset to 50
        data.data.forEach((incident, index) => { //For each incident, run the code
            if (yOffset > 250) { //If the yOffset is greater than 250, add a new page
                pdf.addPage(); //Add a new page
                yOffset = 20; //Set the yOffset to 20
            }

            pdf.setFontSize(14);
            pdf.setTextColor(51, 150, 255);
            pdf.setFont('helvetica', 'bold');
            pdf.text(`Incidencia ${index + 1}:`, 10, yOffset);
            yOffset += 7; //Add 7 to the yOffset

            pdf.setFont('helvetica', 'normal');
            pdf.setFontSize(12);
            pdf.setTextColor(0, 0, 0);

            pdf.text(`Descripción: ${incident.description}`, 20, yOffset);
            yOffset += 7;
            pdf.text(`Prioridad: ${incident.priority}`, 20, yOffset);
            yOffset += 7;
            pdf.text(`Estado: ${incident.status}`, 20, yOffset);
            yOffset += 7;
            pdf.text(`Técnico: ${incident.technician_name || 'No asignado'}`, 20, yOffset);
            yOffset += 7;
            pdf.text(`Fecha: ${new Date(incident.registered_date).toLocaleDateString()}`, 20, yOffset); //Add the date to the pdf
            yOffset += 15; //Add 15 to the yOffset
        });

        pdf.save(`incidencias_${data.machine.name.replace(/\s+/g, '_')}.pdf`); //Save the pdf
    } catch (error) {
        console.error('Error generating PDF:', error); //Show an error
        showToast('Error al generar el PDF: ' + error.message, 'error'); //Show an error
    }
}

window.generateIncidentPDF = generateIncidentPDF; //Export the function

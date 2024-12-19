import { jsPDF } from 'jspdf';

async function generateIncidentPDF(machineId) {
    try {
        const response = await fetch(`/history/incidents/${machineId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Error al cargar el historial');
        }

        const pdf = new jsPDF();
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

        let yOffset = 50;
        data.data.forEach((incident, index) => {
            if (yOffset > 250) {
                pdf.addPage();
                yOffset = 20;
            }

            pdf.setFontSize(14);
            pdf.setTextColor(51, 150, 255);
            pdf.setFont('helvetica', 'bold');
            pdf.text(`Incidencia ${index + 1}:`, 10, yOffset);
            yOffset += 7;

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
            pdf.text(`Fecha: ${new Date(incident.registered_date).toLocaleDateString()}`, 20, yOffset);
            yOffset += 15;
        });

        pdf.save(`incidencias_${data.machine.name.replace(/\s+/g, '_')}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        showToast('Error al generar el PDF: ' + error.message, 'error');
    }
}

window.generateIncidentPDF = generateIncidentPDF;

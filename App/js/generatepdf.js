import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

window.generateMachinesPDF = function (machines) {
    try {
        if (!machines || machines.length === 0) {
            alert("No hay datos disponibles para generar el PDF.");
            return;
        }

        const doc = new jsPDF();

        // Título del PDF
        doc.text("Reporte de Máquinas", 14, 20);

        // Convierte los datos en un formato para jsPDF-Autotable
        const tableRows = machines.map(machine => [
            machine.name || "N/A",
            machine.model || "N/A",
            machine.manufacturer || "N/A",
            machine.location || "N/A",
            machine.installation_date || "N/A",
            machine.coordinates || "N/A"
        ]);

        // Encabezados de la tabla
        const tableHeaders = [
            "Name",
            "Model",
            "Manufacturer",
            "Location",
            "Installation Date",
            "Coordinates"
        ];

        // Agrega la tabla al PDF
        autoTable(doc, {
            head: [tableHeaders],
            body: tableRows,
            startY: 30,
            styles: { fontSize: 10, cellPadding: 4 },
            headStyles: { fillColor: [0, 0, 0], textColor: [255, 255, 255] },
            alternateRowStyles: { fillColor: [240, 240, 240] },
        });

        // Guarda el PDF con un nombre dinámico
        const timestamp = new Date().toISOString().split("T")[0];
        doc.save(`reporte_maquinas_${timestamp}.pdf`);

        alert("PDF generado correctamente");
    } catch (error) {
        console.error("Error generando el PDF:", error);
        alert("Hubo un problema generando el PDF.");
    }
};

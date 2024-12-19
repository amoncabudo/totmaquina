// Define la función showMachineQRCode
window.showMachineQRCode = function(machineId) {
    console.log("Generando QR para la máquina ID:", machineId);
    const url = `/generate_machine_qr/${machineId}`;
    window.open(url, "_blank", "width=500,height=500");
};
    
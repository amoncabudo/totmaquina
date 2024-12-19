window.showMachineQRCode = function(machineId) { //When the function showMachineQRCode is called, run the code
    console.log("Generando QR para la máquina ID:", machineId); //Show a message
    const url = `/generate_machine_qr/${machineId}`; //Set the url to /generate_machine_qr/${machineId}
    window.open(url, "_blank", "width=500,height=500"); //Open the url in a new window with and height of 500
};
    
import $ from "jquery";
window.deleteMachine=function(machineId) {
  if (confirm('¿Estás seguro de que quieres eliminar esta máquina?')) { // If the user confirms the deletion
      fetch(`/deletemachine/${machineId}`, { // Delete the machine
          method: 'POST'
      })
      .then(response => response.json()) // Get the response from the server
      .then(data => {
          if (data.success) { // If the deletion is successful
              // Remove the machine from the DOM
              document.getElementById(`machine-${machineId}`).remove();
          } else {
              alert('Error al eliminar la máquina'); // If there is an error, show an alert
          }
      })
      .catch(error => console.error('Error:', error)); // If there is an error, log the error
  }
}

document.addEventListener('DOMContentLoaded', function() { // When the DOM content is loaded
    var el = document.getElementById('/editmachine');
    if (el != null) {
      el.addEventListener('submit', function(event) { // When the edit machine form is submitted
      event.preventDefault(); // Prevent the default form submission

      const formData = new FormData(this); // Get the form data

      fetch('/editmachine', { // Edit the machine
          method: 'POST',
          body: formData
      });
    });
   }

  const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]'); // Get the dropdown buttons
  dropdownButtons.forEach(button => { // For each dropdown button
      button.addEventListener('click', function() { // When the dropdown button is clicked
          const dropdownMenu = this.nextElementSibling; // Get the dropdown menu
          dropdownMenu.classList.toggle('hidden'); // Toggle the hidden class on the dropdown menu
      });
  });

// Add function to generate PDF for specific machine
function generateMachinesPDF() { //When the function generateMachinesPDF is called, run the code
    const machineId = this.closest('[id^="dropdown-"]').id.split('-')[1]; //Get the machine id
    if (machineId) { //If the machine id is not null, run the code
        generateIncidentPDF(machineId); //Call the function generateIncidentPDF with the machine id as a parameter
    } else {
        showToast('Error: No se pudo identificar la máquina', 'error');
    }
}
});

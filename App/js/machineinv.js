document.addEventListener('DOMContentLoaded', function() {
    const captureButton = document.getElementById('capture-photo'); // Capture button
    if (captureButton) {
        captureButton.addEventListener('click', async () => { // Capture button event listener
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) { // If the browser supports media devices and getUserMedia
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true }); // Get the user media
                    video.srcObject = stream; // Set the video source to the stream
                    video.classList.remove('hidden'); // Remove the hidden class from the video
                    captureButton.textContent = 'Tomar Foto'; // Set the text of the capture button to 'Tomar Foto'
                    
                    captureButton.onclick = () => {
                        context.drawImage(video, 0, 0, 320, 240); // Draw the image from the video to the canvas
                        video.classList.add('hidden'); // Add the hidden class to the video
                        canvas.classList.remove('hidden'); // Remove the hidden class from the canvas
                        captureButton.textContent = 'Capturar desde Webcam'; // Set the text of the capture button to 'Capturar desde Webcam'
                        stream.getTracks().forEach(track => track.stop()); // Stop the stream
                        saveImage(); // Save the image
                    };
                } catch (error) {
                    console.error('Error accessing webcam: ', error); // If there is an error accessing the webcam, log the error
                }
            }
        });
    }

    // Define la función showMachineQRCode
    window.showMachineQRCode = function(machineId) { // Show the machine QR code
        console.log("Generando QR para la máquina ID:", machineId); // Log the machine ID
        window.location.href = `/generate_machine_qr/${machineId}`; // Redirect to the generate machine QR code page
    };
});


const video = document.getElementById('video'); // Get the video element
const canvas = document.getElementById('canvas'); // Get the canvas element
const captureButton = document.getElementById('capture-photo'); // Get the capture button element
const context = canvas.getContext('2d'); // Get the context of the canvas


function deleteMachine(machineId) {
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
  document.getElementById('edit-machine-form').addEventListener('submit', function(event) { // When the edit machine form is submitted
      event.preventDefault(); // Prevent the default form submission

      const formData = new FormData(this); // Get the form data

      fetch('/editmachine', { // Edit the machine
          method: 'POST',
          body: formData
      });
  });

  const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]'); // Get the dropdown buttons
  dropdownButtons.forEach(button => { // For each dropdown button
      button.addEventListener('click', function() { // When the dropdown button is clicked
          const dropdownMenu = this.nextElementSibling; // Get the dropdown menu
          dropdownMenu.classList.toggle('hidden'); // Toggle the hidden class on the dropdown menu
      });
  });

  // Close the menu when clicking outside of it
  document.addEventListener('click', function(event) { // When the document is clicked
      dropdownButtons.forEach(button => { // For each dropdown button
          const dropdownMenu = button.nextElementSibling;
          if (!button.contains(event.target) && !dropdownMenu.contains(event.target)) { // If the button is not clicked and the dropdown menu is not clicked
              dropdownMenu.classList.add('hidden'); // Add the hidden class to the dropdown menu
          }
      });
  });
});

$(document).ready(function() { // When the document is ready
  function toggleButtonText() { // Toggle the button text
      if ($(window).width() < 640) { // If the resolution is less than 640px, hide the text
          $('.add-machine-button .text, .add-csv-button .text').hide(); // Hide the text of both buttons    
      } else {
          $('.add-machine-button .text').show(); // Show the text of the add machine button
          $('.add-csv-button .text').show(); // Show the text of the add machine CSV button
          
      }
  }

  // Call the function when the page loads
  toggleButtonText();

  // Call the function when the window is resized
  $(window).resize(function() {
      toggleButtonText();
  });
});

   // Script to handle the drag and drop of technicians
   document.addEventListener('DOMContentLoaded', function() {
    const disponibles = document.getElementById('tecnicos-disponibles'); // Get the available technicians list
    const asignados = document.getElementById('tecnicos-asignados'); // Get the assigned technicians list
    const selectedTechnicians = document.getElementById('selected-technicians'); // Get the selected technicians field

    if (disponibles && asignados) { // If the available technicians list and the assigned technicians list exist
        // Function to update the hidden field with assigned technicians IDs
        function updateSelectedTechnicians() {
            const assignedTechnicians = Array.from(asignados.children).map(li => li.dataset.id); // Get the assigned technicians IDs
            selectedTechnicians.value = assignedTechnicians.join(','); // Set the value of the selected technicians field to the assigned technicians IDs
            
            // Send update to server
            fetch(`/update-machine-technicians/${machineId}`, { // Update the machine technicians
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    technicians: assignedTechnicians
                })
            })
            .then(response => response.json()) // Get the response from the server
            .then(data => {
                if (data.success) { // If the update is successful
                    console.log('Technicians updated successfully'); // Log the success
                }
            })
            .catch(error => console.error('Error:', error)); // If there is an error, log the error
        }

        // Initialize Sortable for available technicians list
        new Sortable(disponibles, {
            group: 'tecnicos',
            animation: 150,
            onSort: updateSelectedTechnicians,
            onAdd: updateSelectedTechnicians,
            onRemove: updateSelectedTechnicians
        });

        // Initialize Sortable for assigned technicians list
        new Sortable(asignados, {
            group: 'tecnicos',
            animation: 150,
            onSort: updateSelectedTechnicians,
            onAdd: updateSelectedTechnicians,
            onRemove: updateSelectedTechnicians
        });

        // Update hidden field on page load
        updateSelectedTechnicians();
    }
});
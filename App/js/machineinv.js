document.addEventListener('DOMContentLoaded', function() {
    const captureButton = document.getElementById('capture-photo');
    if (captureButton) {
        captureButton.addEventListener('click', async () => {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    captureButton.textContent = 'Tomar Foto';
                    
                    captureButton.onclick = () => {
                        context.drawImage(video, 0, 0, 320, 240);
                        video.classList.add('hidden');
                        canvas.classList.remove('hidden');
                        captureButton.textContent = 'Capturar desde Webcam';
                        stream.getTracks().forEach(track => track.stop());
                        saveImage(); // Guardar la imagen
                    };
                } catch (error) {
                    console.error('Error accessing webcam: ', error);
                }
            }
        });
    }

    // Define la función showMachineQRCode
    window.showMachineQRCode = function(machineId) {
        console.log("Generando QR para la máquina ID:", machineId);
        window.location.href = `/generate_machine_qr/${machineId}`;
    };
});


const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture-photo');
const context = canvas.getContext('2d');


function deleteMachine(machineId) {
  if (confirm('¿Estás seguro de que quieres eliminar esta máquina?')) {
      fetch(`/deletemachine/${machineId}`, {
          method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Elimina la máquina del DOM
              document.getElementById(`machine-${machineId}`).remove();
          } else {
              alert('Error al eliminar la máquina');
          }
      })
      .catch(error => console.error('Error:', error));
  }
}

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('edit-machine-form').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);

      fetch('/editmachine', {
          method: 'POST',
          body: formData
      });
  });

  const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]');
  dropdownButtons.forEach(button => {
      button.addEventListener('click', function() {
          const dropdownMenu = this.nextElementSibling;
          dropdownMenu.classList.toggle('hidden');
      });
  });

  // Close the menu when clicking outside of it
  document.addEventListener('click', function(event) {
      dropdownButtons.forEach(button => {
          const dropdownMenu = button.nextElementSibling;
          if (!button.contains(event.target) && !dropdownMenu.contains(event.target)) {
              dropdownMenu.classList.add('hidden');
          }
      });
  });
});

$(document).ready(function() {
  function toggleButtonText() {
      if ($(window).width() < 640) { // Si la resolución es menor a 640px, ocultar el texto
          $('.add-machine-button .text, .add-csv-button .text').hide(); // Ocultar el texto de los botones
      } else {
          $('.add-machine-button .text').show(); // Mostrar el texto del botón de añadir máquina
          $('.add-csv-button .text').show(); // Mostrar el texto del botón de añadir máquina CSV
          
      }
  }

  // Llamar a la función cuando la página se carga
  toggleButtonText();

  // Llamar a la función cuando la ventana se redimensiona
  $(window).resize(function() {
      toggleButtonText();
  });
});
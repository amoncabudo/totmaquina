function showQRCode(machineId) {
    fetch(`/generate_qr.php?id=${machineId}`)
        .then(response => response.text())
        .then(data => {
            const qrWindow = window.open("", "QR Code", "width=300,height=300");
            qrWindow.document.write(data); // Directly write SVG data
        })
        .catch(error => console.error('Error generating QR code:', error));
}


  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const captureButton = document.getElementById('capture-photo');
  const context = canvas.getContext('2d');

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

  document.getElementById('add-machine-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario tradicional

    const formData = new FormData(this);

    fetch('/addmachine', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualiza el DOM aquí, por ejemplo, añadiendo la nueva máquina a la lista
        } else {
            alert('Error al añadir la máquina');
        }
    })
    .catch(error => console.error('Error:', error));
  });

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

  document.getElementById('edit-machine-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('/editmachine', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualiza el DOM con los nuevos datos de la máquina
        } else {
            alert('Error al editar la máquina');
        }
    })
    .catch(error => console.error('Error:', error));
  });

  
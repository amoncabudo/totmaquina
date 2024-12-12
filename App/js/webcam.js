const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const photo = document.getElementById('photo');
const takePhotoButton = document.getElementById('take-photo');

// Configuración de la cámara
const constraints = {
    video: {
        width: { ideal: 1280 },
        height: { ideal: 720 },
        facingMode: "user"
    }
};

// Función para abrir la cámara
window.startCamera=async function() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;
    } catch (err) {
        console.error('Error accessing camera:', err);
        alert('No se pudo acceder a la cámara. Por favor, asegúrate de dar los permisos necesarios.');
    }
}

// Función para capturar la foto
window.takePhoto=function() {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convertir la imagen a blob
    canvas.toBlob(async (blob) => {
        const imageUrl = URL.createObjectURL(blob);
        photo.src = imageUrl; // Mostrar la imagen capturada
        photo.style.display = 'block'; // Hacer visible la imagen
    }, 'image/jpeg', 0.8);
}

if (document.getElementById("webcam")) {
    // Iniciar la cámara al cargar la página
    window.addEventListener('load', startCamera);
    takePhotoButton.addEventListener('click', takePhoto);
}
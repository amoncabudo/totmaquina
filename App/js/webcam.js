document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture-photo');
    
    if (!video || !canvas || !captureButton) {
        console.warn('Required elements for webcam functionality not found');
        return;
    }

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
});
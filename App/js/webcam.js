document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture-photo');
    
    // Check if required elements are present
    if (!video || !canvas || !captureButton) {
        console.warn('Required elements for webcam functionality not found');
        return;
    }

    const context = canvas.getContext('2d');

    // Event listener for the capture button
    captureButton.addEventListener('click', async () => {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            try {
                // Access the webcam
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                video.classList.remove('hidden');
                captureButton.textContent = 'Take Photo';
                
                // Change the button functionality to capture the photo
                captureButton.onclick = () => {
                    context.drawImage(video, 0, 0, 320, 240);
                    video.classList.add('hidden');
                    canvas.classList.remove('hidden');
                    captureButton.textContent = 'Capture from Webcam';
                    stream.getTracks().forEach(track => track.stop());
                    saveImage(); // Save the image
                };
            } catch (error) {
                console.error('Error accessing webcam: ', error);
            }
        }
    });
});
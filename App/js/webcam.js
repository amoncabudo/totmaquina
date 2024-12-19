if (document.getElementById("capture-photo")) {
document.addEventListener('DOMContentLoaded', function() {
    const openCameraBtn = document.getElementById('open-camera');
    const webcamContainer = document.getElementById('webcam-container');
    const webcam = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('capture-photo');
    const capturedPhoto = document.getElementById('captured-photo');
    const fileInput = document.getElementById('machine-photo');
    let stream = null;

    openCameraBtn.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            webcam.srcObject = stream;
            webcamContainer.classList.remove('hidden');
        } catch (err) {
            console.error('Error accessing webcam:', err);
            alert('Error accessing webcam. Please make sure you have granted camera permissions.');
        }
    });

    captureBtn.addEventListener('click', () => {
        // Set canvas dimensions to match video
        canvas.width = webcam.videoWidth;
        canvas.height = webcam.videoHeight;
        
        // Draw video frame to canvas
        const context = canvas.getContext('2d');
        context.drawImage(webcam, 0, 0, canvas.width, canvas.height);
        
        // Convert canvas to blob
        canvas.toBlob((blob) => {
            // Create a File object from the blob
            const file = new File([blob], 'webcam-capture.jpg', { type: 'image/jpeg' });
            
            // Create a FileList-like object
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Set the file input's files
            fileInput.files = dataTransfer.files;
            
            // Display captured photo
            capturedPhoto.src = URL.createObjectURL(blob);
            capturedPhoto.classList.remove('hidden');
            
            // Stop webcam stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            webcam.srcObject = null;
            webcamContainer.classList.add('hidden');
        }, 'image/jpeg', 0.8);
    });

    // Clean up webcam stream when modal is closed
    const modal = document.getElementById('machine-modal');
    modal.addEventListener('hidden.bs.modal', () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        webcam.srcObject = null;
        webcamContainer.classList.add('hidden');
        capturedPhoto.classList.add('hidden');
    });
});
}
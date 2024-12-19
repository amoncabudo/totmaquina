if (document.getElementById("capture-photo")) { //Check if the capture photo button exists, if exists, run the code
document.addEventListener('DOMContentLoaded', function() {
    const openCameraBtn = document.getElementById('open-camera'); //Get the open camera button
    const webcamContainer = document.getElementById('webcam-container'); //Get the webcam container
    const webcam = document.getElementById('webcam'); //Get the webcam
    const canvas = document.getElementById('canvas'); //Get the canvas
    const captureBtn = document.getElementById('capture-photo'); //Get the capture photo button
    const capturedPhoto = document.getElementById('captured-photo'); //Get the captured photo
    const fileInput = document.getElementById('machine-photo'); //Get the machine photo input
    let stream = null; //Initialize the stream

    openCameraBtn.addEventListener('click', async () => { //Add event listener to the open camera button
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true }); //Get the user media
            webcam.srcObject = stream; //Set the webcam source to the stream
            webcamContainer.classList.remove('hidden'); //Remove the hidden class from the webcam container
        } catch (err) {
            console.error('Error accessing webcam:', err);
            alert('Error accessing webcam. Please make sure you have granted camera permissions.');
        }
    });

    captureBtn.addEventListener('click', () => { //Add event listener to the capture photo button
        // Set canvas dimensions to match video
        canvas.width = webcam.videoWidth; //Set the canvas width to the webcam width
        canvas.height = webcam.videoHeight; //Set the canvas height to the webcam height
        
        // Draw video frame to canvas
        const context = canvas.getContext('2d'); //Get the canvas context
        context.drawImage(webcam, 0, 0, canvas.width, canvas.height); //Draw the video frame to the canvas
        
        canvas.toBlob((blob) => { //Convert the canvas to a blob    
            const file = new File([blob], 'webcam-capture.jpg', { type: 'image/jpeg' }); //Create a file object from the blob
            
            const dataTransfer = new DataTransfer(); //Create a FileList-like object
            dataTransfer.items.add(file); //Add the file to the data transfer
            
            fileInput.files = dataTransfer.files; //Set the file input's files
            
            capturedPhoto.src = URL.createObjectURL(blob); //Display captured photo
            capturedPhoto.classList.remove('hidden'); //Remove the hidden class from the captured photo
            
            if (stream) { //Stop the webcam stream
                stream.getTracks().forEach(track => track.stop()); //Stop the stream tracks
            }
            webcam.srcObject = null; //Set the webcam source to null
            webcamContainer.classList.add('hidden'); //Add the hidden class to the webcam container
        }, 'image/jpeg', 0.8);
    });

    const modal = document.getElementById('machine-modal'); //Get the machine modal
    modal.addEventListener('hidden.bs.modal', () => { //Add event listener to the machine modal
        if (stream) { //Stop the webcam stream
            stream.getTracks().forEach(track => track.stop()); //Stop the stream tracks
        }
        webcam.srcObject = null; //Set the webcam source to null
        webcamContainer.classList.add('hidden'); //Add the hidden class to the webcam container
        capturedPhoto.classList.add('hidden'); //Add the hidden class to the captured photo
    });
});
}
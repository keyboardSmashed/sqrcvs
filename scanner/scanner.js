document.addEventListener('DOMContentLoaded', function () {
    // Check if the browser supports getUserMedia
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        console.error('getUserMedia is not supported');
        return;
    }

    // Get the video element to display the camera feed
    const videoElement = document.getElementById('video');
    // Get the output div
    const outputDiv = document.getElementById('output');
    // Get the student image element
    const studentImage = document.getElementById('student-image'); // Newly added
    const studentInfo = document.getElementById('student-info'); // Newly added

    let isPlaying = false; // Flag to prevent multiple play calls

    // Function to display the "Scan your QR Code here" message
    function displayScanMessage() {
        outputDiv.innerHTML = 'Scan your QR Code here';
    }

    // Create a new instance of Instascan Scanner
    const scanner = new Instascan.Scanner({ video: videoElement });

    // Listen for the "scan" event
    scanner.addListener('scan', function (content) {
        // Send the QR code data to a PHP script for further processing
        fetch('process-qrcode.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ qr_code_data: content }),
        })
        .then(response => response.json())
        .then(data => {
            // Log the entire response for debugging
            console.log(data);

            // Display notification message in the output div
            outputDiv.innerHTML = data.notification;

            // Check if the response is successful and contains the image URL
            if (data.status === 'success' && data.data && data.data.image_url) {
                // Update the student image source and make it visible
                studentImage.src = data.data.image_url; // Set the image source from response
                studentImage.style.display = 'block'; // Show the image
            } else {
                // If there's an error, hide the image
                studentImage.style.display = 'none'; // Hide image on error
            }

            // Clear the notification message after a certain time (e.g., 2 seconds)
            setTimeout(() => {
                outputDiv.innerHTML = '';
                // Hide the student image after timeout
                studentImage.style.display = 'none'; // Hide image again
                // Display "Scan your QR Code here" message immediately after clearing the notification message
                displayScanMessage();
            }, 2000); // 2000 milliseconds = 2 seconds
        })
        .catch(error => {
            console.error('Error processing QR code:', error);
        });
    });

    // Access the camera
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function (stream) {
            // Stop existing tracks if there is a current stream
            if (videoElement.srcObject) {
                let existingStream = videoElement.srcObject;
                let tracks = existingStream.getTracks();
                tracks.forEach(track => track.stop()); // Stop existing tracks
            }

            // Assign the new camera stream to the video element
            videoElement.srcObject = stream;
            videoElement.load(); // Load the new source

            // Ensure the video is ready to play
            videoElement.addEventListener('loadedmetadata', function() {
                if (!isPlaying) {
                    videoElement.play().then(() => {
                        isPlaying = true; // Set the flag to true
                    }).catch(error => {
                        console.error('Error trying to play the video:', error);
                    });
                }
            });

            // Display "Scan your QR Code here" message initially
            displayScanMessage();
        })
        .catch(function (error) {
            console.error('Error accessing camera:', error);
        });

    // Start scanning
    Instascan.Camera.getCameras()
        .then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        })
        .catch(function (error) {
            console.error('Error getting cameras:', error);
        });
});

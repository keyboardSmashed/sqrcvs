<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script defer src="../scanner/scanner.js"></script>
    <link rel="stylesheet" href="scanner-styles.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>QR Scanner</title>
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); /* Gradient background */
            color: #ffffff; /* Text color to match dashboard */
        }

        .container-sm {
            margin-top: 20px; /* Add spacing at the top */
        }

        .row {
            height: 100vh; /* Full viewport height for vertical centering */
            display: flex; /* Enable flexbox */
            align-items: center; /* Center vertically */
        }

        .video-container {
            display: flex;
            flex-direction: column; /* Stack video and card vertically */
            justify-content: center;
            align-items: center;
        }

        #video {
            width: 300px; /* Fixed width */
            height: 300px; /* Fixed height for square shape */
            border-radius: 12px; /* Matching border radius */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Shadow effect */
            object-fit: cover; /* Make the video cover the entire square */
            margin-bottom: 20px; /* Space between video and card */
        }

        .card {
            background-color: rgba(255, 165, 0, 0.8); /* Semi-transparent background color matching hover color */
            border-radius: 12px; /* Matching border radius */
            min-width: 300px; /* Minimum width for the card */
            text-align: center; /* Center text inside the card */
        }

        .card-body {
            padding: 20px; /* Padding for card body */
        }

        .text-dark {
            color: #212529; /* Match with the text color */
        }

        .fw-bold {
            font-weight: bold; /* Bold text */
        }
    </style>
</head>

<body>
    <div class="container-sm">
        <div class="row">
            <div class="col-6 d-flex justify-content-center align-items-center">
                <div class="video-container">
                    <video id="video" autoplay></video>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-dark fs-4 fw-bold" id="output">Scan your QR Code here</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 d-flex justify-content-center align-items-center">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-dark fw-bold">Student Information</h5>
                        <img id="student-image" src="" alt="Student Image" class="img-fluid mb-3" style="display: none;" />
                        <p id="student-info" class="text-dark">Details will appear here after scanning...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

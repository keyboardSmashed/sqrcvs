<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Display</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f4f4f9; /* Light gray background */
        }
        img {
            border: 2px solid #000; /* Black border for the QR code */
            padding: 10px; /* Padding around the QR code */
            background-color: #fff; /* White background for the QR code */
            border-radius: 8px; /* Rounded corners */
            max-width: 100%; /* Responsive image */
            width: 15rem; /* Maintain aspect ratio */
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <?php
            // Retrieve student name and section from URL parameters
            $studentName = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Unknown Student';

            // Display student name and section
            echo "<h1>$studentName</h1>";

            // Display the QR code
            if (isset($_GET['qr'])) {
                $qrFilePath = urldecode($_GET['qr']); // Decode the QR file path from the URL
                echo "<img src='" . htmlspecialchars($qrFilePath) . "' alt='Student QR Code' class='img-fluid'>";
            } else {
                echo "<p class='alert alert-warning'>QR Code not found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

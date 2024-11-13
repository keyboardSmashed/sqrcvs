<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- local style.css file -->
    <link rel="stylesheet" href="styles-html.css">
    <script defer src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script defer src="./scanner/scanner.js"></script>

    <style>
        .overlay {
            position: absolute;
            top: 16.5%;
            left: 34.6%;
            /* Semi-transparent white background */
            padding: 10px;
        }

        .overlay-vid {
            position: absolute;
            top: .5%;
            left: 0%;
            /* Semi-transparent white background */
            padding: 10px;
        }

        .video-qr {
            width: 100%;
            height: 149px;
            object-fit: cover;
        }
    </style>
    <title>NEUST QR CODE VERIFICATION</title>
</head>

<body class="bg bg-dark">

    <div class="container-sm">
        <div class="d-flex justify-content-center ms-4" style="margin-top: 10em; position: relative;">
            <img class="img-fluid w-75" src="./img/scan-and-neust-logo-arrow.png">
            <!-- <div class="overlay rounded container-sm p-0" style="width: 11.7%;">
                <video class="video-qr rounded-4 overlay-vid p-0" id="video"></video>
            </div> -->
        </div>
        <div class="text-center text-light fs-4 fw-bold" id="output"></div>
    </div>

    <!-- Button trigger modal login-->
    <div class="d-flex justify-content-center mt-5">
        <button type="button" class="btn btn-light rounded-pill fs-5 w-25" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
            Login
        </button>
    </div>

    <!-- Modal login -->
    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container d-flex justify-content-center align-items-center bg-dark-subtle">
                    <h2 class="text-dark-subtle mt-4 mb-2">Administrator Login</h2>
                </div>
                <form action="../sqrcvs/process-login.php" method="post">
                    <div class="modal-body bg-dark-subtle">
                        <div class="text-center ps-5 pe-5">
                            <label for="uname" class="ms-1 mb-1">Username</label>
                            <input class="form-control text-center" type="text" id="uname" name="username" required>
                        </div>
                        <div class="text-center ps-5 pe-5 mt-4">
                            <label for="password" class="ms-1 mb-1">Password</label>
                            <input class="form-control text-center" type="password" id="password" name="password" required>
                        </div>
                        <div class="text-center mt-3 mb-3">
                            <button type="submit" class="btn btn-primary" style="width: 80px;">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
<?php
session_start();
require_once '../db_conn.php';

// if (!isset($_SESSION['admin_id'])) {
//     // User is already logged in, redirect to dashboard or any other page
//     header("Location: ../index.php");
//     exit();
// }

// Prepare and execute query to fetch user data from admin_accounts_tbl
$sql = "SELECT * FROM admin_accounts WHERE admin_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $_SESSION['admin_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $_SESSION['img'] = $admin['admin_img'];
}

// for uploading the image in the database
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    // Database connection
    $mysqli = require "../db_conn.php"; // Include your database connection script

    // File upload directory
    $uploadDirectory = "../img/img-profile/admin/"; // Remove extra quotes
    // Get file details
    $fileName = $_FILES["image"]["name"];
    $fileTmpName = $_FILES["image"]["tmp_name"];
    $fileSize = $_FILES["image"]["size"];
    $fileError = $_FILES["image"]["error"];

    // Check if file is uploaded without errors
    if ($fileError === 0) {
        // Generate a unique file name to prevent overwriting existing files
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('', true) . '.' . $fileExtension;
        $uploadPath = $uploadDirectory . $newFileName;

        // Move the uploaded file to the desired location
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Insert file path into the database
            $sql = "UPDATE admin_accounts SET admin_img = ? WHERE admin_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $uploadPath, $_SESSION['admin_id']);
            $stmt->execute();
            $stmt->close();

            echo "File uploaded successfully.";
            header('Location: admin_dash.php');
            exit(); // Ensure no further code is executed
        } else {
            echo "Error uploading file.";
        }
        unset($_SESSION['errTxt']);
    } else {
        echo "Error: " . $fileError;
        $errorTxt = "Please select an image";
        $_SESSION['errTxt'] = $errorTxt;
    }
}
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
    <script defer src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script defer src="./scan_preview.js"></script>
    <title>Dashboard</title>
</head>

<body>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Setup a Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">Finish setting up your profile by adding a Profile picture.</div>
                    <form class="form-control p-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <input class="form-control" type="file" name="image" accept="image/*" id="imageInput">
                        <em class="text-secondary" style="font-size: .8em;">Note: image resolution must not exceed 150x150px.</em>
                        <?php if (isset($_SESSION['errTxt'])) { ?>
                            <div class="mt-2 text-center text-danger"><?php echo $_SESSION['errTxt']; ?></div>
                        <?php } ?>
                        <!-- Default profile icon -->
                        <div class="d-flex justify-content-center mt-3">
                            <!-- display error message -->
                            <img class="rounded-4" src="../img/defaul-preview.png" alt="Default Profile Icon" id="defaultProfileIcon" style="display: block; max-width: 150px; max-height: 150px;">
                            <!-- Image preview (hidden by default) -->
                            <img class="rounded-2" src="#" alt="Preview" id="imagePreview" style="display: none; max-width: 150px; max-height: 150px;">
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-success w-25 rounded-pill" type="submit" name="submit">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded-pill" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once '../admin/navbar_new.php';
    include_once '../admin/admin_view.php';
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there is an error or if the session image is null
            <?php if (empty($_SESSION['img'])): ?>
                var myModal = new bootstrap.Modal(document.getElementById('myModal'));
                myModal.show();
            <?php endif; ?>

            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const defaultProfileIcon = document.getElementById('defaultProfileIcon');

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        defaultProfileIcon.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                    defaultProfileIcon.style.display = 'block';
                }
            });
        });
    </script>
</body>

</html>
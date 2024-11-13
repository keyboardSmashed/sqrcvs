<?php
session_start();
include_once '../phpqrcode-master/qrlib.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../db_conn.php";

    // Directory to store uploaded profile images and QR codes
    $uploadDir = '../img/img-profile/student/'; // Profile picture upload directory
    $qrDir = '../qrcodes/'; // QR code image directory

    // Initialize variables for file paths
    $dest_path = '';  // For the profile picture path

    // Check if the profile image file is uploaded
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile-pic']['tmp_name'];
        $fileName = $_FILES['profile-pic']['name'];
        $fileSize = $_FILES['profile-pic']['size'];
        $fileType = $_FILES['profile-pic']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Set allowed file extensions for profile picture
        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the file has a valid extension
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Create a unique file name for the profile picture
            $newFileName = $_POST['stud-id'] . '_profile.' . $fileExtension;
            $dest_path = $uploadDir . $newFileName;

            // Move the file to the upload directory
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                echo "Error moving the uploaded profile picture file.";
                exit();
            }
        } else {
            echo "Invalid profile picture file extension. Allowed extensions: jpg, jpeg, png, gif.";
            exit();
        }
    } else {
        echo "No profile picture uploaded or there was an upload error.";
        exit();
    }

    // Add "TAL-" before storing in the database
    $stud_id_mod = "TAL" . $_POST['stud-id'];

    // Combine the student information to create a unique string for QR code
    $combined_info = $_POST['stud-id'] . $_POST['fname'] . $_POST['mname'] . $_POST['lname'] . $_POST['bday'] . $_POST['gender'] . $_POST['contact'] . $_POST['province'] . $_POST['city'] . $_POST['zip'] . $_POST['street'] . $_POST['email'];

    // Hash the combined information
    $hashed_info = hash('sha256', $combined_info);

    // File path where the QR code will be saved
    $qr_file = $qrDir . $_POST['stud-id'] . ".png";

    // Generate the QR code and save it to a file
    QRcode::png($hashed_info, $qr_file);

    // Assign POST values to variables for binding
    $first_name = $_POST['fname'];
    $mid_name = $_POST['mname'];
    $last_name = $_POST['lname'];
    $bday = $_POST['bday'];
    $gender = $_POST['gender'];
    $contact_no = $_POST['contact'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $street = $_POST['street'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $admin_id = $_SESSION['admin_id'];

    // Prepare SQL to insert the student's info, profile picture path, hashed text, and QR code file path into students_info
    $sql = "INSERT INTO students_info (stud_id, first_name, mid_name, last_name, bday, sex, contact_no, province, city, zip, street, email, status, admin_id, image, qrcode, qrtext) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Bind parameters for student information
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssiisss",
        $stud_id_mod,
        $first_name,
        $mid_name,
        $last_name,
        $bday,
        $gender,
        $contact_no,
        $province,
        $city,
        $zip,
        $street,
        $email,
        $status,
        $admin_id,
        $dest_path,   // Profile picture path
        $qr_file,     // QR code file path
        $hashed_info  // Hashed information
    );

    try {
        // Execute the SQL statement to insert student info
        if ($stmt->execute()) {
            // Assign $_POST and $_SESSION values to individual variables
            $section_id = $_POST['section'];
            $department_id = $_POST['department'];
            $admin_id = $_SESSION['admin_id'];
            $semester_id = $_POST['semester'];
            $year_level_id = $_POST['year_level'];
            $tag_status = 1; // active status
            // Student added successfully, now insert into tagging table

            // Insert the student's tagging information into the tagging table
            $taggingSql = "INSERT INTO student_tag (stud_id, section_id, dep_id, admin_id, sem_id, year_id, tag_status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)";

            // Bind parameters for tagging information
            $taggingStmt = $mysqli->prepare($taggingSql);
            $taggingStmt->bind_param(
                "siiiiii",
                $stud_id_mod,       // stud_id
                $section_id,        // section_id
                $department_id,     // dep_id
                $admin_id,          // admin_id
                $semester_id,       // sem_id
                $year_level_id,     // year_id
                $tag_status         // tag_status (active)
            );

            // Execute the SQL statement to insert tagging info
            if ($taggingStmt->execute()) {
                // Concatenate student name
                $studentName = htmlspecialchars($_POST['fname'] . ' ' . $_POST['mname'] . ' ' . $_POST['lname']);
                // Redirect to the page that displays the QR code along with student name
                header("Location: show_qr_code.php?qr=" . urlencode($qr_file) . "&name=" . urlencode($studentName));
                exit();
            } else {
                // Output any error if the execution fails
                echo "Failed to add tagging information. Error: (" . $taggingStmt->errno . ") " . $taggingStmt->error;
                exit();
            }
        } else {
            echo "Failed to add student.";
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Catch and handle duplicate entry error
        if ($e->getCode() === 1062) {
            echo "Duplicate entry for student ID";
            exit();
        } else {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
}

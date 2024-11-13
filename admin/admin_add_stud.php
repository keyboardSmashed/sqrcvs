<?php
session_start();

include_once '../db_conn.php';

// Query to select department names
$sql_department = "SELECT * FROM department"; // Adjust table name and columns as needed
$result_department = $mysqli->query($sql_department);

$options_department = "";
if ($result_department->num_rows > 0) {
    // Fetch all department names and create an option tag for each one
    while ($row = $result_department->fetch_assoc()) {
        $options_department .= '<option value="' . htmlspecialchars($row["dep_id"]) . '">' . htmlspecialchars($row["dep_name"]) . '</option>';
    }
} else {
    // Handle case when no data is available
    $options_department .= '<option value="">No departments available</option>';
}

// Query to select section names
$sql_section = "SELECT * FROM section"; // Adjust table name and columns as needed
$result_section = $mysqli->query($sql_section);

$options_section = "";
if ($result_section->num_rows > 0) {
    // Fetch all section names and create an option tag for each one
    while ($row = $result_section->fetch_assoc()) {
        $options_section .= '<option value="' . htmlspecialchars($row["section_id"]) . '">' . htmlspecialchars($row["section_name"]) . '</option>';
    }
} else {
    // Handle case when no data is available
    $options_section .= '<option value="">No sections available</option>';
}

// Query to select year level names
$sql_year_level = "SELECT * FROM year_level"; // Adjust table name and columns as needed
$result_year_level = $mysqli->query($sql_year_level);

$options_year_level = "";
if ($result_year_level->num_rows > 0) {
    // Fetch all section names and create an option tag for each one
    while ($row = $result_year_level->fetch_assoc()) {
        $options_year_level .= '<option value="' . htmlspecialchars($row["year_id"]) . '">' . htmlspecialchars($row["year_name"]) . '</option>';
    }
} else {
    // Handle case when no data is available
    $options_year_level .= '<option value="">No year level available</option>';
}

// Query to select school year
$sql_sy = "SELECT * FROM school_year"; // Adjust table name and columns as needed
$result_sy = $mysqli->query($sql_sy);

$options_sy = "";
if ($result_sy->num_rows > 0) {
    // Fetch all section names and create an option tag for each one
    while ($row = $result_sy->fetch_assoc()) {
        $start_date_sy = new DateTime($row['start_date']);
        $end_date_sy = new DateTime($row['end_date']);
        $formatted_start_date_sy = $start_date_sy->format('F d, Y');
        $formatted_end_date_sy = $end_date_sy->format('F d, Y');
        $options_sy .= '<option value="' . htmlspecialchars($row["sy_id"]) . '">' . htmlspecialchars($row["sy_name"]) . " - " . $formatted_start_date_sy . " to " . $formatted_end_date_sy . '</option>';
    }
} else {
    $options_sy .= '<option value="">No semester available</option>';
}

// Query to select semester
$sql_semester = "SELECT * FROM semester"; // Adjust table name and columns as needed
$result_semester = $mysqli->query($sql_semester);

$options_semester = "";
if ($result_semester->num_rows > 0) {
    while ($row = $result_semester->fetch_assoc()) {
        $start_date = new DateTime($row['start_date']);
        $end_date = new DateTime($row['end_date']);
        $formatted_start_date = $start_date->format('F d, Y');
        $formatted_end_date = $end_date->format('F d, Y');
        $options_semester .= '<option value="' . htmlspecialchars($row["sem_id"]) . '">' . htmlspecialchars($row["sem_name"]) . " - " . $formatted_start_date . " to " . $formatted_end_date . '</option>';
    }
} else {
    $options_semester .= '<option value="">No semester available</option>';
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 1rem;
            /* Space above the container */
        }

        .drop-zone {
            border: 2px dashed #ffc107;
            border-radius: 10px;
            /* Rounded corners */
            padding: 10px;
            text-align: center;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            /* Smooth transition */
        }

        .drop-zone.dragover {
            background-color: #e2f0ff;
            /* Light blue background when dragging */
            color: #0056b3;
            /* Darker text color */
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            transition: border-color 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .btn-success {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .label {
            font-weight: bold;
        }

        .card-body {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Remove the dropdown arrow from the select element */
        .no-arrow {
            -webkit-appearance: none;
            /* For Chrome and Safari */
            -moz-appearance: none;
            /* For Firefox */
            appearance: none;
            /* For other browsers */
            background: transparent;
            padding-right: 0;
            /* Remove padding where the arrow was */
        }
    </style>
    <title>Add Student</title>
</head>

<body>
    <!-- New drop-zone for Excel file upload -->
    <div class="container">
        <div class="drop-zone text-warning" id="drop-zone">
            Drag and drop student information excel file here or click to upload <br>
            <i class="text-center text-secondary fs-6">Supported formats: .xlsx, .xls</i>
        </div>
        <input type="file" id="fileInput" accept=".xlsx, .xls" style="display: none;">
    </div>
    <div class="container-fluid mt-3 d-flex justify-content-center">
        <div class="card mb-3" style="width: 75rem;">
            <div class="card-body">
                <h1 class="text-center mb-5">Add Student</h1>
                <form action="./process_add_stud.php" method="post" enctype="multipart/form-data"> <!-- Add enctype attribute -->
                    <label class="label mb-3 fs-5">Personal Information</label><br>
                    <div class="row">
                        <div class="col-sm">
                            <label for="fname" class="m-1">First Name</label>
                            <span class="text-danger">*</span>
                            <input id="fname" name="fname" class="form-control" type="text" required>
                        </div>
                        <div class="col-sm">
                            <label for="mname" class="m-1">Middle Name</label>
                            <input id="mname" name="mname" class="form-control" type="text">
                        </div>
                        <div class="col-sm">
                            <label for="lname" class="m-1">Last Name</label>
                            <span class="text-danger">*</span>
                            <input id="lname" name="lname" class="form-control" type="text" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm">
                            <label for="bday" class="m-1">Date of Birth</label>
                            <span class="text-danger">*</span>
                            <i class="ms-3" style="color: gray; font-size: 0.8rem;">Format: DD/MM/YYYY</i>
                            <input id="bday" name="bday" class="form-control" type="date" required>
                        </div>
                        <div class="col-sm">
                            <label for="gender" class="m-1">Gender</label>
                            <span class="text-danger">*</span>
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="" hidden>Select Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm">
                            <label for="contact" class="m-1">Contact Number</label>
                            <input id="contact" name="contact" class="form-control" type="text" required>
                        </div>
                        <div class="col-sm">
                            <label for="email" class="m-1">Email Address</label>
                            <span class="text-danger">*</span>
                            <input id="email" name="email" class="form-control" type="email" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm">
                            <label for="street" class="m-1">Street</label>
                            <span class="text-danger">*</span>
                            <input id="street" name="street" class="form-control" type="text" required>
                        </div>
                        <div class="col-sm">
                            <label for="province" class="m-1">Province</label>
                            <span class="text-danger">*</span>
                            <input id="province" name="province" class="form-control" type="text" required>
                        </div>
                        <div class="col-sm">
                            <label for="city" class="m-1">City</label>
                            <span class="text-danger">*</span>
                            <input id="city" name="city" class="form-control" type="text" required>
                        </div>
                        <div class="col-sm">
                            <label for="zip" class="m-1">Zip Code</label>
                            <span class="text-danger">*</span>
                            <input id="zip" name="zip" class="form-control" type="text" required>
                        </div>
                    </div>

                    <!-- Image upload field -->
                    <label class="mt-4 mb-3 label fs-5">Profile Picture</label><br>
                    <div class="row">
                        <div class="col">
                            <label for="profile-pic" class="m-1">Upload Profile Picture</label>
                            <span class="text-danger">*</span>
                            <input id="profile-pic" name="profile-pic" class="form-control" type="file" accept="image/*" required onchange="previewImage(event)">
                        </div>
                    </div>
                    <!-- Preview area for the uploaded image -->
                    <div class="row justify-content-center mt-3">
                        <div class="col-auto">
                            <img id="imagePreview" src="#" alt="Image Preview" style="display:none; max-width: 200px; border-radius: 10px;" />
                        </div>
                    </div>

                    <label class="label mt-4 mb-3 fs-5">College Information</label><br>
                    <div class="row">
                        <div class="col-sm">
                            <label for="stud-id" class="m-1">Student ID</label>
                            <span class="text-danger">*</span>
                            <div class="input-group">
                                <select class="form-select no-arrow" style="max-width: 60px;" disabled>
                                    <option>TAL -</option>
                                </select>
                                <input id="stud-id" name="stud-id" class="form-control" type="text" required>
                            </div>
                        </div>
                        <div class="col-sm">
                            <label for="status" class="m-1">Status</label>
                            <span class="text-danger">*</span>
                            <select id="status" name="status" class="form-select" required>
                                <option value="" hidden>Select Status</option>
                                <option value="1">Regular</option>
                                <option value="2">Irregular</option>
                                <option value="3">Returning</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm">
                            <label for="department" class="m-1">Department</label>
                            <span class="text-danger">*</span>
                            <select id="department" name="department" class="form-select" required>
                                <option value="" hidden>Select Department</option>
                                <?php echo $options_department; ?>
                            </select>
                        </div>
                        <div class="col-sm">
                            <label for="year_level" class="m-1">Year Level</label>
                            <span class="text-danger">*</span>
                            <select id="year_level" name="year_level" class="form-select" required>
                                <option value="" hidden>Select Year Level</option>
                                <?php echo $options_year_level; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm">
                            <label for="section" class="m-1">Section</label>
                            <span class="text-danger">*</span>
                            <select id="section" name="section" class="form-select" required>
                                <option value="" hidden>Select Section</option>
                                <?php echo $options_section; ?>
                            </select>
                        </div>
                        <div class="col-sm">
                            <label for="sy" class="m-1">School Year</label>
                            <span class="text-danger">*</span>
                            <select id="sy" name="sy" class="form-select" required>
                                <option value="" hidden>Select School Year</option>
                                <?php echo $options_sy; ?>
                            </select>
                        </div>
                        <div class="col-sm">
                            <label for="semester" class="m-1">Semester</label>
                            <span class="text-danger">*</span>
                            <select id="semester" name="semester" class="form-select" required>
                                <option value="" hidden>Select Semester</option>
                                <?php echo $options_semester; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn btn-success" type="submit">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    function previewImage(event) {
        const image = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                image.src = reader.result;
                image.style.display = 'block'; // Show the image preview
            };
            reader.readAsDataURL(file); // Convert the image file to a data URL
        } else {
            image.src = '';
            image.style.display = 'none'; // Hide the image preview if no file is selected
        }
    }

    

    //Drag and drop excel file script
    const dropZone = document.getElementById("drop-zone");
    const fileInput = document.getElementById("fileInput");

    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("dragover");
    });

    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("dragover");
        const files = e.dataTransfer.files;
        uploadFile(files[0]);
    });

    dropZone.addEventListener("click", () => {
        fileInput.click();
    });

    fileInput.addEventListener("change", () => {
        const files = fileInput.files;
        uploadFile(files[0]);
    });

    function uploadFile(file) {
        const formData = new FormData();
        formData.append("excelFile", file);

        fetch("process_add_to_db.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    alert(result.error);
                } else {
                    alert(`Successfully added ${result.count} students to the database.`);
                    // Store records in local storage
                    localStorage.setItem('uploadedRecords', JSON.stringify(result.records));
                    // Open the preview page in a new tab
                    window.open('preview.php', '_blank');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred during the upload process.");
            });
    }
</script>

</html>
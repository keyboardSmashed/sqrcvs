<?php
require_once '../db_conn.php'; // Include your database connection file

// Check if all required parameters are set
if (isset($_POST['subject_dep_id'], $_POST['subject_year_id'], $_POST['new_sub_code'], $_POST['new_sub_name'])) {
    $dep_id = $mysqli->real_escape_string($_POST['subject_dep_id']);
    $year_id = $mysqli->real_escape_string($_POST['subject_year_id']);
    $sub_code = $mysqli->real_escape_string($_POST['new_sub_code']);
    $sub_name = $mysqli->real_escape_string($_POST['new_sub_name']);

    // Prepare the SQL query to insert the new subject
    $query = "INSERT INTO subjects (dep_id, year_id, sub_code, sub_name) 
              VALUES ('$dep_id', '$year_id', '$sub_code', '$sub_name')";

    if ($mysqli->query($query)) {
        echo "Subject added successfully!";
    } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "All fields are required!";
}

$mysqli->close(); // Close the database connection
?>

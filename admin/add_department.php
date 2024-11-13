<?php
require_once '../db_conn.php'; // Include your database connection file

// Check if the 'new_dep_name' parameter is set
if (isset($_POST['new_dep_name'])) {
    $dep_name = $mysqli->real_escape_string($_POST['new_dep_name']);

    // Prepare the SQL query to insert the new department
    $query = "INSERT INTO department (dep_name) VALUES ('$dep_name')";

    if ($mysqli->query($query)) {
        echo "Department added successfully!";
    } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "Department name is required!";
}

$mysqli->close(); // Close the database connection
?>

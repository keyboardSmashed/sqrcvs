<?php
require_once '../db_conn.php'; // Include your database connection file

// Check if the 'new_section_name' parameter is set
if (isset($_POST['new_section_name'])) {
    $section_name = $mysqli->real_escape_string($_POST['new_section_name']);

    // Prepare the SQL query to insert the new section
    $query = "INSERT INTO section (section_name) VALUES ('$section_name')";

    if ($mysqli->query($query)) {
        echo "Section added successfully!";
    } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "Section name is required!";
}

$mysqli->close(); // Close the database connection
?>

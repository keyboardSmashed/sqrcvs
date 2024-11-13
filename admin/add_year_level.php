<?php
// add_year_level.php
require_once '../db_conn.php'; // Your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_year_name = $_POST['new_year_name'];

    // Insert the new year level into the database
    $stmt = $mysqli->prepare("INSERT INTO year_level (year_name) VALUES (?)");
    $stmt->bind_param("s", $new_year_name);

    if ($stmt->execute()) {
        echo "Year level added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

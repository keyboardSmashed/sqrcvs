<?php
require_once '../db_conn.php';

// Query to fetch all sections
$query = "SELECT * FROM section ORDER BY section_name ASC";
$result = $mysqli->query($query);

$sections = [];
while ($row = $result->fetch_assoc()) {
    $sections[] = $row;  // Add each section to the array
}

$mysqli->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($sections);
?>

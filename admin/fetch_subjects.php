<?php
require_once '../db_conn.php';

// Query to fetch all subjects with related department and year level
$query = "
    SELECT s.sub_id, s.sub_code, s.sub_name, d.dep_name, yl.year_name 
    FROM subjects s
    JOIN department d ON s.dep_id = d.dep_id
    JOIN year_level yl ON s.year_id = yl.year_id
    ORDER BY s.sub_code ASC";
$result = $mysqli->query($query);

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;  // Add each subject to the array
}

$mysqli->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($subjects);
?>

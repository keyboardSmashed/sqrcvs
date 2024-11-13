<?php
require_once '../db_conn.php';

$query = "SELECT year_id, year_name FROM year_level";
$result = $mysqli->query($query);

$yearLevels = [];
while ($row = $result->fetch_assoc()) {
    $yearLevels[] = $row;
}

$mysqli->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($yearLevels);
?>

<?php
require_once '../db_conn.php';

$query = "SELECT dep_id, dep_name FROM department";
$result = $mysqli->query($query);

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

$mysqli->close();
header('Content-Type: application/json');
echo json_encode($departments);
?>

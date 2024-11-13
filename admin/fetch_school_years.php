<?php
require_once '../db_conn.php';

$query = "SELECT sy_id, sy_name FROM school_year WHERE end_date >= CURDATE() ORDER BY sy_id DESC";
$result = $mysqli->query($query);

$schoolYears = [];
while ($row = $result->fetch_assoc()) {
    $schoolYears[] = $row;
}

echo json_encode($schoolYears);

$mysqli->close();
?>

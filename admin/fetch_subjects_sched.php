<?php
require_once '../db_conn.php';

if (isset($_GET['department_id']) && isset($_GET['year_level_id'])) {
    $department_id = $_GET['department_id'];
    $year_level_id = $_GET['year_level_id'];

    // Query to fetch subjects based on department and year level
    $query = "SELECT * FROM subjects WHERE dep_id = ? AND year_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $department_id, $year_level_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    // Return data as JSON
    echo json_encode($subjects);
}
?>

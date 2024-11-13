<?php
require_once '../db_conn.php'; // Ensure this is the correct path for your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve the data sent via POST
    $sched_id = $_POST['sched_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Ensure you're using prepared statements to prevent SQL injection
    $query = "UPDATE scheduling SET day_of_week = ?, start_time = ?, end_time = ? WHERE sched_id = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare query']);
        exit;
    }

    // Bind the parameters (assuming all are strings except sched_id, which is an integer)
    $stmt->bind_param('sssi', $day_of_week, $start_time, $end_time, $sched_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

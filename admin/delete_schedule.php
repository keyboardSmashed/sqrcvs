<?php
require_once '../db_conn.php';

if (isset($_POST['sched_id'])) {
    $schedId = $_POST['sched_id'];

    // Prepare the SQL query to delete the schedule
    $stmt = $mysqli->prepare("DELETE FROM scheduling WHERE sched_id = ?");
    $stmt->bind_param("i", $schedId);

    if ($stmt->execute()) {
        echo 'success'; // Respond with success
    } else {
        echo 'error'; // Respond with error if the delete fails
    }

    $stmt->close();
} else {
    echo 'error'; // Respond with error if no sched_id is provided
}

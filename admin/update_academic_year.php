<?php
require_once '../db_conn.php';

// Check if all necessary fields are provided
if (isset($_POST['sy_id'], $_POST['sem_id'], $_POST['sy_name'], $_POST['sy_start_date'], $_POST['sy_end_date'], $_POST['sem_name'], $_POST['sem_start_date'], $_POST['sem_end_date'])) {
    // Retrieve POST data
    $sy_id = $_POST['sy_id']; // The academic year ID
    $sem_id = $_POST['sem_id']; // The semester ID
    $sy_name = $_POST['sy_name'];
    $sy_start_date = $_POST['sy_start_date'];
    $sy_end_date = $_POST['sy_end_date'];
    $sem_name = $_POST['sem_name'];
    $sem_start_date = $_POST['sem_start_date'];
    $sem_end_date = $_POST['sem_end_date'];

    // Prepare SQL query to update the current academic year and semester
    $updateQuery = "
        UPDATE school_year sy
        JOIN semester sem ON sy.sy_id = sem.sy_id  -- Corrected join condition
        SET sy.sy_name = ?, sy.start_date = ?, sy.end_date = ?,
            sem.sem_name = ?, sem.start_date = ?, sem.end_date = ?
        WHERE sy.sy_id = ? AND sem.sem_id = ?";  // Ensure the correct semester and academic year are updated
    
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('ssssssii', $sy_name, $sy_start_date, $sy_end_date, $sem_name, $sem_start_date, $sem_end_date, $sy_id, $sem_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

$mysqli->close();
?>

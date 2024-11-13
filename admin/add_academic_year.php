<?php
require_once '../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_sy_name'])) {
        // Add Academic Year
        $sy_name = $_POST['new_sy_name'];
        $sy_start_date = $_POST['new_sy_start_date'];
        $sy_end_date = $_POST['new_sy_end_date'];

        $query = "INSERT INTO school_year (sy_name, start_date, end_date) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $sy_name, $sy_start_date, $sy_end_date);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Academic year added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add academic year.']);
        }
        $stmt->close();
    } elseif (isset($_POST['new_sem_name']) && isset($_POST['available_sy'])) {
        // Add Semester
        $sem_name = $_POST['new_sem_name'];
        $sem_start_date = $_POST['new_sem_start_date'];
        $sem_end_date = $_POST['new_sem_end_date'];
        $sy_id = $_POST['available_sy'];

        $query = "INSERT INTO semester (sem_name, start_date, end_date, sy_id) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssi', $sem_name, $sem_start_date, $sem_end_date, $sy_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Semester added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add semester.']);
        }
        $stmt->close();
    }
}

$mysqli->close();
?>

<?php
session_start();
require_once '../db_conn.php';

header('Content-Type: application/json');

// Initialize response array
$response = [];

try {
    // Query to fetch scan logs with student names
    $sql = "SELECT sl.stud_id, CONCAT(si.first_name, ' ', si.mid_name, ' ', si.last_name) AS full_name, sl.`date-time`, sl.type 
            FROM scan_logs sl 
            JOIN students_info si ON sl.stud_id = si.stud_id 
            ORDER BY sl.`date-time` ASC"; // Adjust as needed

    $result = $mysqli->query($sql);

    if ($result) {
        $scan_logs = [];
        while ($row = $result->fetch_assoc()) {
            $scan_logs[] = $row; // Collect logs
        }
        $response['status'] = 'success';
        $response['data'] = $scan_logs; // Store logs here
    } else {
        throw new Exception("Error retrieving scan logs: " . $mysqli->error);
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'An error occurred: ' . $e->getMessage();
}

// Send response
echo json_encode($response);
$mysqli->close();
?>

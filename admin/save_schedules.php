<?php
require_once '../db_conn.php';
$data = json_decode(file_get_contents('php://input'), true);

$response = ["success" => false];

if (!empty($data)) {
    $stmt = $mysqli->prepare("INSERT INTO scheduling (sub_id, section_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?)");

    foreach ($data as $schedule) {
        $sub_id = $schedule['subject'];
        $section_id = $schedule['section'];
        $day_of_week = $schedule['day'];
        $start_time = $schedule['startTime'];
        $end_time = $schedule['endTime'];

        $stmt->bind_param("iisss", $sub_id, $section_id, $day_of_week, $start_time, $end_time);
        $stmt->execute();
    }

    $response["success"] = true;
    $stmt->close();
}

echo json_encode($response);
?>

<?php
// Include database connection
include_once '../db_conn.php';

// Check if required POST parameters are received
if (isset($_POST['dep_id']) && isset($_POST['year_id']) && isset($_POST['section_id'])) {
    $dep_id = $_POST['dep_id'];
    $year_id = $_POST['year_id'];
    $section_id = $_POST['section_id'];

    // Query to fetch matching schedules
    $query = "SELECT scheduling.sched_id, scheduling.day_of_week, scheduling.start_time, scheduling.end_time, subjects.sub_name
              FROM scheduling
              INNER JOIN subjects ON scheduling.sub_id = subjects.sub_id
              WHERE subjects.dep_id = '$dep_id'
              AND subjects.year_id = '$year_id'
              AND scheduling.section_id = '$section_id'";

    $result = mysqli_query($mysqli, $query);

    // Prepare an array to hold schedule options
    $schedules = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $schedules[] = [
            'sched_id' => $row['sched_id'],
            'sub_name' => $row['sub_name'],
            'day_of_week' => $row['day_of_week'],
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time']
        ];
    }

    // Return schedules as JSON
    echo json_encode($schedules);

}
?>

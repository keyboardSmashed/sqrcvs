<?php
require_once '../db_conn.php';

// Get today's date
$currentDate = date('Y-m-d');

// Query to fetch academic years and corresponding semesters that are currently active (started and ongoing)
$query = "
    SELECT sy.sy_id, sy.sy_name, sy.start_date AS sy_start_date, sy.end_date AS sy_end_date, 
           sem.sem_id, sem.sem_name, sem.start_date AS sem_start_date, sem.end_date AS sem_end_date
    FROM school_year sy
    JOIN semester sem ON sy.sy_id = sem.sy_id
    WHERE sy.start_date <= ? AND sy.end_date >= ? 
    AND sem.start_date <= ? AND sem.end_date >= ?  -- Ensures the semester has started and is ongoing
    ORDER BY sy.sy_id DESC"; // Fetch the current academic year based on today's date

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ssss', $currentDate, $currentDate, $currentDate, $currentDate);  // Use current date for comparison
$stmt->execute();
$result = $stmt->get_result();

// Create an array to hold academic years and their respective semesters
$academicYears = [];
while ($row = $result->fetch_assoc()) {
    $academicYears[] = $row;
}

// Query to fetch the most recent academic years (optional) based on active status
$recentYearsQuery = "
    SELECT sy.sy_id, sy.sy_name, sy.start_date AS sy_start_date, sy.end_date AS sy_end_date, 
           sem.sem_id, sem.sem_name, sem.start_date AS sem_start_date, sem.end_date AS sem_end_date
    FROM school_year sy
    JOIN semester sem ON sy.sy_id = sem.sy_id
    WHERE sy.end_date <= ?  -- School year has ended before or on today's date
    AND sem.end_date <= ?  -- Semester has ended before or on today's date
    ORDER BY sy.sy_id DESC 
    LIMIT 5;";  // You can adjust the limit to fit your needs

$recentResult = $mysqli->prepare($recentYearsQuery);
$recentResult->bind_param('ss', $currentDate, $currentDate);
$recentResult->execute();
$recentResult = $recentResult->get_result();

while ($row = $recentResult->fetch_assoc()) {
    // Push the recent years into the array (excluding duplicates)
    if (!in_array($row, $academicYears)) {
        $academicYears[] = $row;
    }
}

$mysqli->close();

// Return the result as JSON
header('Content-Type: application/json');  // Set the response content type to JSON
echo json_encode($academicYears);

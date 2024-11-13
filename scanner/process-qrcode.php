<?php
session_start();

// Include the database connection
require_once '../db_conn.php';

// Set the default timezone to UTC
date_default_timezone_set('Asia/Manila'); // You can change 'UTC' to your desired timezone

// Initialize response array
$response = [];

try {
    // Retrieve the QR code data sent from the JavaScript
    $qrCodeData = json_decode(file_get_contents('php://input'), true);

    // Check if the QR code data is set
    if (isset($qrCodeData['qr_code_data'])) {
        // Process the QR code data
        $qrData = $qrCodeData['qr_code_data'];

        // Prepare and execute the query to check for the student ID based on the QR code
        $sql = "SELECT stud_id, first_name, mid_name, last_name, image FROM students_info WHERE qrtext = ?";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("s", $qrData);
        if (!$stmt->execute()) {
            throw new Exception("Error executing SQL statement: " . $stmt->error);
        }
        $result = $stmt->get_result();

        // Check for errors
        if (!$result) {
            throw new Exception("Error retrieving result set: " . $mysqli->error);
        }

        // Check if a matching record was found
        if ($result->num_rows > 0) {
            // Fetch student information
            $row = $result->fetch_assoc();
            $stud_id = $row['stud_id'];
            $first_name = $row['first_name'];
            $middle_name = $row['mid_name'];
            $last_name = $row['last_name'];
            $image_url = $row['image']; // Get the image URL

            // Log the image URL for debugging
            error_log("Image URL: " . $image_url); // Debugging line to check image URL

            // Retrieve the current date and time in UTC
            $currentTime = date('Y-m-d H:i:s', time()); // Current time in UTC
            $currentDate = date('Y-m-d', time()); // Current date in UTC

            // Function to record scan log
            function recordScanLog($mysqli, $stud_id, $currentTime, $type, $status)
            {
                $sql = "INSERT INTO scan_logs (stud_id, type, `date-time`, status) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparing SQL statement: " . $mysqli->error);
                }
                // Adjusting the bind_param to match expected types
                $stmt->bind_param("sisi", $stud_id, $type, $currentTime, $status); // Assuming stud_id is a string

                if (!$stmt->execute()) {
                    throw new Exception("Error executing SQL statement: " . $stmt->error);
                }
            }

            // Check the latest scan log for the student
            $sql = "SELECT * FROM scan_logs WHERE stud_id = ? ORDER BY `date-time` DESC LIMIT 1"; 
            $stmt = $mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing SQL statement: " . $mysqli->error);
            }
            $stmt->bind_param("s", $stud_id);
            if (!$stmt->execute()) {
                throw new Exception("Error executing SQL statement: " . $stmt->error);
            }
            $result = $stmt->get_result();

            if (!$result) {
                throw new Exception("Error retrieving result set: " . $mysqli->error);
            }

            $latestLog = $result->fetch_assoc();

            // Determine whether to log an entry or exit
            if ($latestLog) {
                if ($latestLog['type'] == 1 && $latestLog['status'] == 1) {
                    // Last log is an entry, log an exit
                    $scanType = 2; // Exit
                    $scanStatus = 1; // Completed
                    recordScanLog($mysqli, $stud_id, $currentTime, $scanType, $scanStatus);

                    // Set notification message
                    $notificationMessage = 'Goodbye, ' . $first_name . ' ' . $middle_name . ' ' . $last_name . '. You have exited the campus.';
                    $response['status'] = 'success';
                    $response['data']['status'] = 'Exited';
                } else {
                    // Last log is an exit, log a new entry
                    $scanType = 1; // Entry
                    $scanStatus = 1; // Completed
                    recordScanLog($mysqli, $stud_id, $currentTime, $scanType, $scanStatus);

                    // Set notification message
                    $notificationMessage = 'Welcome, ' . $first_name . ' ' . $middle_name . ' ' . $last_name . '. You have entered the campus.';
                    $response['status'] = 'success';
                    $response['data']['status'] = 'Entered';
                }
            } else {
                // If there are no logs, treat this as the first entry
                $scanType = 1; // Entry
                $scanStatus = 1; // Completed
                recordScanLog($mysqli, $stud_id, $currentTime, $scanType, $scanStatus);

                // Set notification message
                $notificationMessage = 'Welcome, ' . $first_name . ' ' . $middle_name . ' ' . $last_name . '. You have entered the campus.';
                $response['status'] = 'success';
                $response['data']['status'] = 'Entered';
            }

            $response['data']['image_url'] = $image_url; // Add image URL to response
            $response['message'] = 'Scan log recorded successfully';
            $response['notification'] = $notificationMessage;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No account found with the provided QR code data';
        }
    } else {
        // Handle case where QR code data is not set
        $response['status'] = 'error';
        $response['message'] = 'QR code data not provided';
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'An error occurred: ' . $e->getMessage();
}

// Send a response back to the JavaScript code
header('Content-Type: application/json'); // Set JSON header
echo json_encode($response);

// Log the response for debugging
error_log(print_r($response, true)); // Log the response to the error log for inspection

// Close the database connection
$mysqli->close();

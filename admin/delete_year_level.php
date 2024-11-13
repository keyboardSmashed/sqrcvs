<?php
require_once '../db_conn.php';  // Ensure this is the correct path for your DB connection

if (isset($_POST['year_id'])) {
    $year_id = $_POST['year_id'];

    // Prepare the SQL query to delete the year level
    $sql = "DELETE FROM year_level WHERE year_id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $year_id); // "i" indicates the type is integer
        if ($stmt->execute()) {
            echo "Year level deleted successfully.";
        } else {
            echo "Error deleting year level.";
        }
        $stmt->close();
    } else {
        echo "Error preparing the query.";
    }

    $mysqli->close();
} else {
    echo "Invalid year ID.";
}
?>

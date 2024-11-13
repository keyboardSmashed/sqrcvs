<?php
// Connect to database
require_once '../db_conn.php';

if (isset($_POST['sub_id'])) {
    $sub_id = $_POST['sub_id'];
    $query = "DELETE FROM subjects WHERE sub_id = ?";
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $sub_id);
        if ($stmt->execute()) {
            echo "Subject deleted successfully!";
        } else {
            echo "Error deleting subject.";
        }
        $stmt->close();
    }
}
?>

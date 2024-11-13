<?php
// Connect to database
require_once '../db_conn.php';

if (isset($_POST['dep_id'])) {
    $dep_id = $_POST['dep_id'];
    $query = "DELETE FROM department WHERE dep_id = ?";
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $dep_id);
        if ($stmt->execute()) {
            echo "Department deleted successfully!";
        } else {
            echo "Error deleting department.";
        }
        $stmt->close();
    }
}
?>

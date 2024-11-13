<?php
// Connect to database
require_once '../db_conn.php';

if (isset($_POST['section_id'])) {
    $section_id = $_POST['section_id'];
    $query = "DELETE FROM section WHERE section_id = ?";
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $section_id);
        if ($stmt->execute()) {
            echo "Section deleted successfully!";
        } else {
            echo "Error deleting section.";
        }
        $stmt->close();
    }
}
?>

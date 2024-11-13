<?php
require_once '../db_conn.php';

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['excelFile']['tmp_name'];
    
    // Load the Excel file
    $spreadsheet = IOFactory::load($fileTmpPath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    $insertedCount = 0;
    $records = []; // Array to hold records for preview

    foreach ($worksheet->getRowIterator(2) as $row) { // Start from the second row to skip headers
        $cells = $row->getCellIterator();
        $cells->setIterateOnlyExistingCells(false);

        $studentData = [];
        foreach ($cells as $cell) {
            $studentData[] = $cell->getValue();
        }

        // Skip rows where 'stud_id' is empty
        if (empty($studentData[0])) {
            continue; // Move to the next row if 'stud_id' is blank
        }

        // Format date properly (assuming 'bday' is in index 4)
        $bday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($studentData[4]);
        $formattedBday = $bday->format('Y-m-d'); // Convert to YYYY-MM-DD

        // Insert data into the database
        $sql = "INSERT INTO students_info (stud_id, first_name, mid_name, last_name, bday, sex, contact_no, province, city, zip, street, email, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssssssssssi", 
            $studentData[0], 
            $studentData[1], 
            $studentData[2], 
            $studentData[3], 
            $formattedBday, // Use the formatted date here
            $studentData[5], 
            $studentData[6], 
            $studentData[7], 
            $studentData[8], 
            $studentData[9], 
            $studentData[10], 
            $studentData[11], 
            $studentData[12]
        );

        if ($stmt->execute()) {
            $insertedCount++;
            $records[] = $studentData; // Add to records array for preview
        }
    }

    // Return response as JSON
    echo json_encode([
        'count' => $insertedCount,
        'records' => $records
    ]);
} else {
    echo json_encode(['error' => 'Failed to upload the file.']);
}

$mysqli->close();
?>

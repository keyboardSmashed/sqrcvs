<?php
require_once '../db_conn.php';

// Fetch all distinct departments, year levels, and sections for filters
$departments = $mysqli->query("SELECT DISTINCT dep_id, dep_name FROM department ORDER BY dep_name");
$yearLevels = $mysqli->query("SELECT DISTINCT year_id, year_name FROM year_level ORDER BY year_name");
$sections = $mysqli->query("SELECT DISTINCT section_id, section_name FROM section ORDER BY section_name");

// Fetch all schedule records
$query = "
        SELECT 
        sch.sched_id,
        d.dep_name,
        y.year_name,
        s.section_name,
        sub.sub_code,
        sub.sub_name,
        sch.day_of_week,
        sch.start_time,
        sch.end_time
    FROM scheduling AS sch
    JOIN subjects AS sub ON sch.sub_id = sub.sub_id
    JOIN section AS s ON sch.section_id = s.section_id
    JOIN year_level AS y ON sub.year_id = y.year_id
    JOIN department AS d ON sub.dep_id = d.dep_id
    ORDER BY d.dep_name, y.year_name, s.section_name, sch.day_of_week, sch.start_time
";
$schedules = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            opacity: 0;
            /* Start invisible */
            transition: opacity .4s ease-in-out;
            /* Fade-in transition */
        }

        body.loaded {
            opacity: 1;
            /* Fade to visible */
        }

        /* Style for readonly fields */
        input[readonly] {
            background-color: #e9ecef;
            /* Light grey background to indicate it's readonly */
            cursor: not-allowed;
            /* Change cursor to indicate it's non-editable */
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: Arial, sans-serif;
            padding: 2rem;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            border-radius: 12px;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
            padding: 15px;
            text-align: center;
        }

        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #dddddd;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        h2 {
            font-weight: 600;
            text-align: center;
            color: #007bff;
            margin-bottom: 1.5rem;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
        }

        .filter-container select {
            width: 250px;
            padding: 0.75rem;
            font-size: 1.1rem;
            border: 2px solid #007bff;
            border-radius: 8px;
            background-color: #f0f9ff;
        }

        .filter-container select:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 8px rgba(0, 91, 187, 0.2);
        }

        /* Style for Edit and Delete buttons with click animation */
        .edit-button,
        .delete-button {
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }

        .edit-button {
            background-color: #17a2b8;
            color: #fff;
        }

        .delete-button {
            background-color: #dc3545;
            color: #fff;
        }

        /* Click animation effect */
        .edit-button:active,
        .delete-button:active {
            transform: scale(0.95);
            /* Slightly shrinks button */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* Adds a shadow effect */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h2>All Schedules</h2>

            <!-- Filter Dropdowns -->
            <div class="filter-container">
                <select id="departmentFilter" class="form-select" onchange="applyFilters()">
                    <option value="">All Departments</option>
                    <?php while ($row = $departments->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row['dep_name']); ?>">
                            <?php echo htmlspecialchars($row['dep_name']); ?>
                        </option>
                    <?php } ?>
                </select>

                <select id="yearLevelFilter" class="form-select" onchange="applyFilters()">
                    <option value="">All Year Levels</option>
                    <?php while ($row = $yearLevels->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row['year_name']); ?>">
                            <?php echo htmlspecialchars($row['year_name']); ?>
                        </option>
                    <?php } ?>
                </select>

                <select id="sectionFilter" class="form-select" onchange="applyFilters()">
                    <option value="">All Sections</option>
                    <?php while ($row = $sections->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row['section_name']); ?>">
                            <?php echo htmlspecialchars($row['section_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Year Level</th>
                            <th>Section</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Day of Week</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Actions</th> <!-- Actions column header -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $schedules->fetch_assoc()) { ?>
                            <?php
                            // Formatting the times
                            $start_time_12 = date("g:i A", strtotime($row['start_time']));
                            $end_time_12 = date("g:i A", strtotime($row['end_time']));
                            ?>

                            <tr class="schedule-row" data-sched-id="<?php echo $row['sched_id']; ?>">
                                <td class="department"><?php echo htmlspecialchars($row['dep_name']); ?></td>
                                <td class="year-level"><?php echo htmlspecialchars($row['year_name']); ?></td>
                                <td class="section"><?php echo htmlspecialchars($row['section_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['sub_code']); ?></td>
                                <td><?php echo htmlspecialchars($row['sub_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                                <td><?php echo $start_time_12; ?></td>
                                <td><?php echo $end_time_12; ?></td>
                                <td>
                                    <button class="edit-button">Edit</button>
                                    <button class="delete-button">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editScheduleForm">
                        <input type="hidden" id="modalSchedId"> <!-- Hidden field for sched_id -->
                        <div class="mb-3">
                            <label for="modalDepartment" class="form-label">Department</label>
                            <input type="text" class="form-control" id="modalDepartment" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalYearLevel" class="form-label">Year Level</label>
                            <input type="text" class="form-control" id="modalYearLevel" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalSection" class="form-label">Section</label>
                            <input type="text" class="form-control" id="modalSection" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalSubjectCode" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="modalSubjectCode" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalSubjectName" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="modalSubjectName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalDayOfWeek" class="form-label">Day of Week</label>
                            <input type="text" class="form-control" id="modalDayOfWeek" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalStartTime" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="modalStartTime" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalEndTime" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="modalEndTime" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveChangesButton">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
        function applyFilters() {
            const departmentFilter = document.getElementById('departmentFilter').value.toLowerCase();
            const yearLevelFilter = document.getElementById('yearLevelFilter').value.toLowerCase();
            const sectionFilter = document.getElementById('sectionFilter').value.toLowerCase();

            const rows = document.querySelectorAll('.schedule-row');
            rows.forEach(row => {
                const department = row.querySelector('.department').textContent.toLowerCase();
                const yearLevel = row.querySelector('.year-level').textContent.toLowerCase();
                const section = row.querySelector('.section').textContent.toLowerCase();

                row.style.display =
                    (department.includes(departmentFilter) || departmentFilter === '') &&
                    (yearLevel.includes(yearLevelFilter) || yearLevelFilter === '') &&
                    (section.includes(sectionFilter) || sectionFilter === '') ? '' : 'none';
            });
        }

        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr'); // Get the row that contains the clicked button

                // Get the sched_id from the row's data-sched-id attribute
                const schedId = row.getAttribute('data-sched-id');

                // Get other data from the row
                const department = row.querySelector('.department').textContent;
                const yearLevel = row.querySelector('.year-level').textContent;
                const section = row.querySelector('.section').textContent;
                const subjectCode = row.querySelector('td:nth-child(4)').textContent; // Subject Code column
                const subjectName = row.querySelector('td:nth-child(5)').textContent; // Subject Name column
                const dayOfWeek = row.querySelector('td:nth-child(6)').textContent; // Day of Week column
                const startTime12 = row.querySelector('td:nth-child(7)').textContent; // Start Time column (in 12-hour format)
                const endTime12 = row.querySelector('td:nth-child(8)').textContent; // End Time column (in 12-hour format)

                // Convert 12-hour time format to 24-hour format for start and end times
                const startTime24 = formatTo24Hour(startTime12);
                const endTime24 = formatTo24Hour(endTime12);

                // Set the modal fields with the row data
                document.getElementById('modalDepartment').value = department;
                document.getElementById('modalYearLevel').value = yearLevel;
                document.getElementById('modalSection').value = section;
                document.getElementById('modalSubjectCode').value = subjectCode;
                document.getElementById('modalSubjectName').value = subjectName;
                document.getElementById('modalDayOfWeek').value = dayOfWeek;
                document.getElementById('modalStartTime').value = startTime24;
                document.getElementById('modalEndTime').value = endTime24;

                // Set the sched_id to be used in the update query
                document.getElementById('modalSchedId').value = schedId;

                // Open the modal
                var myModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
                myModal.show();
            });
        });

        // Function to convert 12-hour time format to 24-hour format
        function formatTo24Hour(time12) {
            const time = new Date('1970-01-01T' + time12 + 'Z'); // Add 'Z' to handle timezone
            const hours = time.getHours();
            const minutes = time.getMinutes();
            return (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;
        }

        // Handle the form submission for saving the changes
        document.getElementById('editScheduleForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Make sure you're getting the updated values from the modal form
            const dayOfWeek = document.getElementById('modalDayOfWeek').value; // Ensure the correct input field
            const startTime = document.getElementById('modalStartTime').value; // Ensure the correct input field
            const endTime = document.getElementById('modalEndTime').value; // Ensure the correct input field

            const schedId = document.getElementById('modalSchedId').value; // Get the sched_id from the modal

            // Prepare the form data to be sent to the server
            const formData = new FormData();
            formData.append('sched_id', schedId);
            formData.append('day_of_week', dayOfWeek);
            formData.append('start_time', startTime);
            formData.append('end_time', endTime);

            // Send the data via AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_schedule.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Schedule updated successfully!");
                            location.reload();
                        } else {
                            alert("Failed to update schedule.");
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                        console.log("Server response:", xhr.responseText);
                    }
                } else {
                    alert("Error in request.");
                }
            };
            xhr.send(formData);
        });

        //delete
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const schedId = row.getAttribute('data-sched-id');

                // Confirm deletion with the user
                if (confirm('Are you sure you want to delete this schedule?')) {
                    // Make the AJAX request to delete the schedule
                    fetch('delete_schedule.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `sched_id=${schedId}`
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data === 'success') {
                                // Remove the row from the table
                                row.remove();
                                // Show the success alert
                                alert('Schedule Deleted Successfully!');
                            } else {
                                alert('Failed to delete schedule!');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the schedule');
                        });
                }
            });
        });
        // Fade-in effect
        window.onload = function() {
            document.body.classList.add('loaded');
        };
    </script>
</body>

</html>
<?php
// Include database connection
include_once '../db_conn.php';

// Fetch departments, year levels, and sections for the form filters
$departments = mysqli_query($mysqli, "SELECT * FROM department");
$year_levels = mysqli_query($mysqli, "SELECT * FROM year_level");
$sections = mysqli_query($mysqli, "SELECT * FROM section");

// Function to fetch students based on filters
function getFilteredStudents($mysqli, $dep_id, $year_id, $section_id)
{
    $query = "SELECT * FROM students_info 
              INNER JOIN student_tag ON students_info.stud_id = student_tag.stud_id 
              WHERE student_tag.dep_id = '$dep_id' 
              AND student_tag.year_id = '$year_id' 
              AND student_tag.section_id = '$section_id'";
    return mysqli_query($mysqli, $query);
}

// Handle form submission for tagging students to schedules
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag_schedule'])) {
    $dep_id = $_POST['dep_id'];
    $year_id = $_POST['year_id'];
    $section_id = $_POST['section_id'];
    $sched_id = $_POST['sched_id'];

    // Fetch students based on filters
    $students = getFilteredStudents($mysqli, $dep_id, $year_id, $section_id);

    while ($student = mysqli_fetch_assoc($students)) {
        $stud_id = $student['stud_id'];

        // Fetch the corresponding stud_tag_id from student_tag
        $stud_tag_query = "SELECT stud_tag_id FROM student_tag WHERE stud_id = '$stud_id' AND dep_id = '$dep_id' AND year_id = '$year_id' AND section_id = '$section_id'";
        $stud_tag_result = mysqli_query($mysqli, $stud_tag_query);

        if ($stud_tag_row = mysqli_fetch_assoc($stud_tag_result)) {
            $stud_tag_id = $stud_tag_row['stud_tag_id'];

            // Check if the student is already tagged to the selected schedule
            $check_query = "SELECT * FROM schedule_tag WHERE stud_tag_id = '$stud_tag_id' AND sched_id = '$sched_id'";
            $check_result = mysqli_query($mysqli, $check_query);

            // Insert into schedule_tag if not already tagged
            if (mysqli_num_rows($check_result) == 0) {
                $insert_query = "INSERT INTO schedule_tag (stud_tag_id, sched_id) VALUES ('$stud_tag_id', '$sched_id')";
                mysqli_query($mysqli, $insert_query);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tag Students to Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-OPk8v+ib9zBQ8zHB5md5OvP8GNYeY1ae6E6sUXo/Qedb2SCxwHb5vhtceQTPffpI" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-WDZNH3lM58hgS1uLP0rXMjmoGJc/5mty2Tb3ilGZ7MoVczJ0v6kY6vx/1ZoO2AHr" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Button to trigger the modal -->
    <button type="button" class="btn btn-warning w-100 mt-2" data-bs-toggle="modal" data-bs-target="#tagStudentModal">
        <i class="bi bi-bookmark-plus"></i> Tag Students
    </button>

    <div class="container">
        <!-- Modal -->
        <div class="modal fade" id="tagStudentModal" tabindex="-1" aria-labelledby="tagStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tagStudentModalLabel">Tag Students to Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Department Selection -->
                            <label for="dep_id">Department:</label>
                            <select name="dep_id" id="dep_id" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php while ($department = mysqli_fetch_assoc($departments)) : ?>
                                    <option value="<?= $department['dep_id'] ?>"><?= $department['dep_name'] ?></option>
                                <?php endwhile; ?>
                            </select>

                            <!-- Year Level Selection -->
                            <label for="year_id" class="mt-2">Year Level:</label>
                            <select name="year_id" id="year_id" class="form-control" required>
                                <option value="">Select Year Level</option>
                                <?php while ($year_level = mysqli_fetch_assoc($year_levels)) : ?>
                                    <option value="<?= $year_level['year_id'] ?>"><?= $year_level['year_name'] ?></option>
                                <?php endwhile; ?>
                            </select>

                            <!-- Section Selection -->
                            <label for="section_id" class="mt-2">Section:</label>
                            <select name="section_id" id="section_id" class="form-control" required>
                                <option value="">Select Section</option>
                                <?php while ($section = mysqli_fetch_assoc($sections)) : ?>
                                    <option value="<?= $section['section_id'] ?>"><?= $section['section_name'] ?></option>
                                <?php endwhile; ?>
                            </select>

                            <!-- Schedule Selection -->
                            <label for="sched_id" class="mt-2">Schedule:</label>
                            <select name="sched_id" id="sched_id" class="form-control" required>
                                <option value="">Select Schedule</option>
                                <!-- Schedule options will be dynamically loaded here -->
                            </select>
                        </div>
                        <div class="modal-footer">
                            <i style="font-size: .8rem;" class="text-secondary">*Tagged students to the selected schedule will not be affected anymore upon tagging</i>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="tag_schedule" class="btn btn-primary">Tag Students</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to load schedules based on selected department, year level, and section
        function loadSchedules() {
            const depId = $('#dep_id').val();
            const yearId = $('#year_id').val();
            const sectionId = $('#section_id').val();

            // Proceed only if all three values are selected
            if (depId && yearId && sectionId) {
                $.ajax({
                    url: 'fetch_schedules.php',
                    type: 'POST',
                    data: {
                        dep_id: depId,
                        year_id: yearId,
                        section_id: sectionId
                    },
                    dataType: 'json',
                    success: function(schedules) {
                        // Clear existing options in schedule dropdown
                        $('#sched_id').empty();
                        $('#sched_id').append('<option value="">Select Schedule</option>');

                        // Populate schedule dropdown with options from AJAX response
                        $.each(schedules, function(index, schedule) {
                            $('#sched_id').append(
                                `<option value="${schedule.sched_id}">${schedule.sub_name} - ${schedule.day_of_week}, ${schedule.start_time} - ${schedule.end_time}</option>`
                            );
                        });
                    }
                });
            } else {
                // Clear schedule dropdown if any filter is not selected
                $('#sched_id').empty();
                $('#sched_id').append('<option value="">Select Schedule</option>');
            }
        }

        // Trigger loadSchedules when department, year level, or section changes
        $('#dep_id, #year_id, #section_id').change(function() {
            loadSchedules();
        });
    </script>

</body>

</html>
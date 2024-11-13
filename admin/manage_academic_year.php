<!-- Button to Manage Academic Year -->
<button class="btn btn-warning w-100 mt-3" id="manageAcademicYearBtn" data-bs-toggle="modal" data-bs-target="#manageAcademicYearModal">
    <i class="bi bi-bookmarks"></i> Manage Academic Year
</button>

<!-- Modal for Managing Academic Year -->
<div class="modal fade" id="manageAcademicYearModal" tabindex="-1" aria-labelledby="manageAcademicYearModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Wider modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageAcademicYearModalLabel">Manage Academic Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Top Left: Current Academic Year Form -->
                    <div class="col-md-6">
                        <h6>Current Academic Year:</h6>
                        <form id="editCurrentAcademicYearForm">
                            <!-- Hidden fields for sy_id and sem_id -->
                            <input type="hidden" id="sy_id" name="sy_id">
                            <input type="hidden" id="sem_id" name="sem_id">

                            <div class="mb-3">
                                <label for="sy_name" class="form-label">Academic Year Name</label>
                                <input type="text" class="form-control" id="sy_name" name="sy_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="sy_start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="sy_start_date" name="sy_start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="sy_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="sy_end_date" name="sy_end_date" required>
                            </div>
                            <h6>Current Semester:</h6>
                            <div class="mb-3">
                                <label for="sem_name" class="form-label">Semester Name</label>
                                <input type="text" class="form-control" id="sem_name" name="sem_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="sem_start_date" class="form-label">Semester Start Date</label>
                                <input type="date" class="form-control" id="sem_start_date" name="sem_start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="sem_end_date" class="form-label">Semester End Date</label>
                                <input type="date" class="form-control" id="sem_end_date" name="sem_end_date" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>

                    <!-- Right Column for Recent Semesters List -->
                    <div class="col-md-6">
                        <h6>Recent Semesters:</h6>
                        <ul id="recent-semesters" class="list-group">
                            <!-- Recent semesters will be dynamically populated here -->
                        </ul>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <!-- Bottom Left: Add New Academic Year Form -->
                    <div class="col-md-6">
                        <h6>Add New Academic Year:</h6>
                        <form id="addAcademicYearForm">
                            <div class="mb-3">
                                <label for="new_sy_name" class="form-label">Academic Year Name</label>
                                <input type="text" class="form-control" id="new_sy_name" name="new_sy_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_sy_start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="new_sy_start_date" name="new_sy_start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_sy_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="new_sy_end_date" name="new_sy_end_date" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Academic Year</button>
                        </form>
                    </div>

                    <!-- Bottom Right: Add New Semester Form -->
                    <div class="col-md-6">
                        <h6>Add New Semester:</h6>
                        <form id="addNewSemesterForm">
                            <div class="mb-3">
                                <label for="available_sy" class="form-label">Available School Year</label>
                                <select class="form-control" id="available_sy" name="available_sy" required>
                                    <option value="">Select School Year</option>
                                    <!-- Dynamically populated school years -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="new_sem_name" class="form-label">Semester Name</label>
                                <input type="text" class="form-control" id="new_sem_name" name="new_sem_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_sem_start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="new_sem_start_date" name="new_sem_start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_sem_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="new_sem_end_date" name="new_sem_end_date" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Semester</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
            // Fetch the academic years from the server using AJAX
            document.getElementById('manageAcademicYearBtn').addEventListener('click', function() {
                fetch('fetch_academic_years.php') // Replace with the actual PHP script that returns academic years
                    .then(response => response.json())
                    .then(data => {
                        const currentAcademicYearForm = document.getElementById('editCurrentAcademicYearForm');
                        const recentSemestersElement = document.getElementById('recent-semesters');

                        // Clear the existing semesters list to avoid duplication
                        recentSemestersElement.innerHTML = '';

                        // Display the current academic year (assuming the first item is the current year)
                        if (data.length > 0) {
                            const currentYearData = data[0]; // Current academic year data
                            document.getElementById('sy_id').value = currentYearData.sy_id; // Set the sy_id
                            document.getElementById('sem_id').value = currentYearData.sem_id; // Set the sem_id
                            document.getElementById('sy_name').value = currentYearData.sy_name;
                            document.getElementById('sy_start_date').value = currentYearData.sy_start_date;
                            document.getElementById('sy_end_date').value = currentYearData.sy_end_date;
                            document.getElementById('sem_name').value = currentYearData.sem_name;
                            document.getElementById('sem_start_date').value = currentYearData.sem_start_date;
                            document.getElementById('sem_end_date').value = currentYearData.sem_end_date;

                            // Display recent academic years and semesters (excluding the current one)
                            data.slice(1).forEach(year => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item');
                                li.innerHTML = `<strong>${year.sy_name}</strong> (${year.sem_name}) <br> Start: ${year.sem_start_date} - End: ${year.sem_end_date}`;
                                recentSemestersElement.appendChild(li);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching academic years:', error);
                    });
            });

            document.getElementById('editCurrentAcademicYearForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Gather form data
                const formData = new FormData(this);

                // Send AJAX request to update the academic year
                fetch('update_academic_year.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Academic Year updated successfully!");
                            // Optionally, refresh the academic years list
                            location.reload();
                        } else {
                            alert("Failed to update the Academic Year: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error updating academic year:", error);
                    });
            });

            document.getElementById('addAcademicYearForm').addEventListener('submit', function(event) {
                event.preventDefault();
                let formData = new FormData(this);

                fetch('add_academic_year.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') {
                            // Optionally, reload school years in the semester form
                            loadSchoolYears();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            document.getElementById('addNewSemesterForm').addEventListener('submit', function(event) {
                event.preventDefault();
                let formData = new FormData(this);

                fetch('add_academic_year.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Load available school years
            function loadSchoolYears() {
                fetch('fetch_school_years.php')
                    .then(response => response.json())
                    .then(data => {
                        let select = document.getElementById('available_sy');
                        select.innerHTML = '<option value="">Select School Year</option>';
                        data.forEach(sy => {
                            let option = document.createElement('option');
                            option.value = sy.sy_id;
                            option.textContent = sy.sy_name;
                            select.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }

            document.addEventListener('DOMContentLoaded', loadSchoolYears);

        </script>
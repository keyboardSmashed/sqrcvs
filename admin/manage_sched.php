<!-- Button to Manage Schedule -->
<button class="btn btn-warning w-100 mt-2" data-bs-toggle="modal" data-bs-target="#manageScheduleModal">
    <i class="bi bi-calendar-check"></i> Manage Schedule
</button>

<!-- Modal for Managing Schedule -->
<div class="modal fade" id="manageScheduleModal" tabindex="-1" aria-labelledby="manageScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageScheduleModalLabel">Manage Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Initial Fields -->
                <div class="mb-3">
                    <label for="departmentSelect" class="form-label">Department</label>
                    <select id="departmentSelect" class="form-control" onchange="loadSubjects(); loadSections();" required>
                        <option value="">Select Department</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="yearLevelSelect" class="form-label">Year Level</label>
                    <select id="yearLevelSelect" class="form-control" onchange="loadSubjects()" required>
                        <option value="">Select Year Level</option>
                    </select>
                </div>

                <!-- Template for Dynamic Schedule Form -->
                <div class="schedule-form" id="scheduleTemplate">
                    <div class="mb-3">
                        <label for="subjectSelect" class="form-label">Subject</label>
                        <select class="form-control subject-select" required>
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sectionSelect" class="form-label">Section</label>
                        <select class="form-control section-select" required>
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dayOfWeekSelect" class="form-label">Day of Week</label>
                        <select class="form-control day-select" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Start Time</label>
                        <input type="time" class="form-control start-time" required>
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">End Time</label>
                        <input type="time" class="form-control end-time" required>
                    </div>
                    <hr>
                </div>

                <!-- Container for Adding Multiple Schedules -->
                <div id="additionalSchedulesContainer"></div>

                <!-- Add Multiple Schedules Button -->
                <button type="button" class="btn btn-success" id="addMultipleBtn">Add Another Schedule</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveScheduleBtn">Save</button>
                <button type="button" class="btn btn-info" onclick="viewAllSchedules()">View All Schedules</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript Function to Open a New Tab with All Schedules
    function viewAllSchedules() {
        window.open('all_schedules.php', '_blank');
    }

    document.getElementById('addMultipleBtn').addEventListener('click', addNewScheduleForm);

    function addNewScheduleForm() {
        const template = document.getElementById('scheduleTemplate');
        const newSchedule = template.cloneNode(true);
        newSchedule.removeAttribute('id'); // Remove ID to avoid duplicates
        document.getElementById('additionalSchedulesContainer').appendChild(newSchedule);
        trackChanges(); // Track changes for newly added form elements
    }

    document.getElementById('saveScheduleBtn').addEventListener('click', function() {
        const schedules = [];

        const scheduleForms = document.querySelectorAll('.schedule-form');
        scheduleForms.forEach((form) => {
            const subject = form.querySelector('.subject-select').value;
            const section = form.querySelector('.section-select').value;
            const day = form.querySelector('.day-select').value;
            const startTime = form.querySelector('.start-time').value;
            const endTime = form.querySelector('.end-time').value;

            if (subject && section && day && startTime && endTime) {
                schedules.push({
                    subject,
                    section,
                    day,
                    startTime,
                    endTime
                });
            }
        });

        fetch('save_schedules.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(schedules),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Schedules saved successfully!');
                    location.reload(); // Optionally reload or reset modal
                } else {
                    alert('Failed to save schedules.');
                }
            })
            .catch(error => console.error('Error saving schedules:', error));
    });

    // Function to load departments
    function loadDepartments() {
        fetch('fetch_departments.php')
            .then(response => response.json())
            .then(data => {
                const departmentSelect = document.getElementById('departmentSelect');
                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.dep_id;
                    option.textContent = department.dep_name;
                    departmentSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching departments:', error));
    }

    // Function to load year levels
    function loadYearLevels() {
        fetch('fetch_year_levels.php')
            .then(response => response.json())
            .then(data => {
                const yearLevelSelect = document.getElementById('yearLevelSelect');
                yearLevelSelect.innerHTML = '<option value="">Select Year Level</option>';

                data.forEach(yearLevel => {
                    const option = document.createElement('option');
                    option.value = yearLevel.year_id;
                    option.textContent = yearLevel.year_name;
                    yearLevelSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching year levels:', error));
    }

    // Function to load subjects based on selected department and year level
    function loadSubjects() {
        const departmentId = document.getElementById('departmentSelect').value;
        const yearLevelId = document.getElementById('yearLevelSelect').value;

        if (departmentId && yearLevelId) {
            fetch(`fetch_subjects_sched.php?department_id=${departmentId}&year_level_id=${yearLevelId}`)
                .then(response => response.json())
                .then(data => {
                    const subjectSelects = document.querySelectorAll('.subject-select');
                    subjectSelects.forEach(select => {
                        select.innerHTML = '<option value="">Select Subject</option>';
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.sub_id;
                            option.textContent = `${subject.sub_code} - ${subject.sub_name}`;
                            select.appendChild(option);
                        });
                    });
                })
                .catch(error => console.error('Error fetching subjects:', error));
        }
    }

    // Function to load sections based on selected department
    function loadSections() {
        const departmentId = document.getElementById('departmentSelect').value;

        if (departmentId) {
            fetch(`fetch_sections.php?department_id=${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    const sectionSelects = document.querySelectorAll('.section-select');
                    sectionSelects.forEach(select => {
                        select.innerHTML = '<option value="">Select Section</option>';
                        data.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.section_id;
                            option.textContent = section.section_name;
                            select.appendChild(option);
                        });
                    });
                })
                .catch(error => console.error('Error fetching sections:', error));
        }
    }

    // Initialize fields when modal is shown
    document.getElementById('manageScheduleModal').addEventListener('shown.bs.modal', function() {
        loadDepartments();
        loadYearLevels();
    });
</script>


<!-- <script>
    let unsavedChanges = false;

    // Set unsavedChanges to true if any input changes in the modal
    function trackChanges() {
        const elements = document.querySelectorAll('#manageScheduleModal input, #manageScheduleModal select');
        elements.forEach(element => {
            element.removeEventListener('change', markUnsaved); // Remove any existing listener
            element.addEventListener('change', markUnsaved); // Add listener once
        });
    }

    // Function to mark unsaved changes
    function markUnsaved() {
        unsavedChanges = true;
    }

    // Reset unsavedChanges flag when Save button is clicked
    document.getElementById('saveScheduleBtn').addEventListener('click', function() {
        unsavedChanges = false; // Reset the flag on successful save
        saveSchedules(); // Call your function to save schedules here
    });

    // Prevent modal from closing if there are unsaved changes (attach this listener once)
    document.getElementById('manageScheduleModal').addEventListener('hide.bs.modal', function(event) {
        if (unsavedChanges) {
            const confirmClose = confirm("You have unsaved changes. Are you sure you want to close?");
            if (!confirmClose) {
                event.preventDefault(); // Prevent modal from closing
            }
        }
    });

    // Call trackChanges() to initialize change listeners when modal is shown
    document.getElementById('manageScheduleModal').addEventListener('shown.bs.modal', function() {
        unsavedChanges = false; // Reset unsavedChanges when the modal opens
        trackChanges(); // Initialize change tracking
    });

    document.getElementById('addMultipleBtn').addEventListener('click', addNewScheduleForm);

    function addNewScheduleForm() {
        const template = document.getElementById('scheduleTemplate');
        const newSchedule = template.cloneNode(true);
        newSchedule.removeAttribute('id'); // Remove ID to avoid duplicates
        document.getElementById('additionalSchedulesContainer').appendChild(newSchedule);
        trackChanges(); // Track changes for new form elements
    }

    document.getElementById('saveScheduleBtn').addEventListener('click', function() {
        const schedules = [];

        const scheduleForms = document.querySelectorAll('.schedule-form');
        scheduleForms.forEach((form) => {
            const subject = form.querySelector('.subject-select').value;
            const section = form.querySelector('.section-select').value;
            const day = form.querySelector('.day-select').value;
            const startTime = form.querySelector('.start-time').value;
            const endTime = form.querySelector('.end-time').value;

            if (subject && section && day && startTime && endTime) {
                schedules.push({
                    subject,
                    section,
                    day,
                    startTime,
                    endTime
                });
            }
        });

        fetch('save_schedules.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(schedules),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Schedules saved successfully!');
                    location.reload(); // Optionally reload or reset modal
                } else {
                    alert('Failed to save schedules.');
                }
            })
            .catch(error => console.error('Error saving schedules:', error));
    });

    // Function to load departments
    function loadDepartments() {
        fetch('fetch_departments.php')
            .then(response => response.json())
            .then(data => {
                const departmentSelect = document.getElementById('departmentSelect');
                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.dep_id;
                    option.textContent = department.dep_name;
                    departmentSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching departments:', error));
    }

    // Function to load year levels
    function loadYearLevels() {
        fetch('fetch_year_levels.php')
            .then(response => response.json())
            .then(data => {
                const yearLevelSelect = document.getElementById('yearLevelSelect');
                yearLevelSelect.innerHTML = '<option value="">Select Year Level</option>';

                data.forEach(yearLevel => {
                    const option = document.createElement('option');
                    option.value = yearLevel.year_id;
                    option.textContent = yearLevel.year_name;
                    yearLevelSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching year levels:', error));
    }

    // Function to load subjects based on selected department and year level
    function loadSubjects() {
        const departmentId = document.getElementById('departmentSelect').value;
        const yearLevelId = document.getElementById('yearLevelSelect').value;

        if (departmentId && yearLevelId) {
            fetch(`fetch_subjects_sched.php?department_id=${departmentId}&year_level_id=${yearLevelId}`)
                .then(response => response.json())
                .then(data => {
                    const subjectSelect = document.querySelectorAll('.subject-select');
                    subjectSelect.forEach(select => {
                        select.innerHTML = '<option value="">Select Subject</option>';
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.sub_id;
                            option.textContent = `${subject.sub_code} - ${subject.sub_name}`;
                            select.appendChild(option);
                        });
                    });
                })
                .catch(error => console.error('Error fetching subjects:', error));
        }
    }

    // Function to load sections based on selected department
    function loadSections() {
        const departmentId = document.getElementById('departmentSelect').value;

        if (departmentId) {
            fetch(`fetch_sections.php?department_id=${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    const sectionSelect = document.querySelectorAll('.section-select');
                    sectionSelect.forEach(select => {
                        select.innerHTML = '<option value="">Select Section</option>';
                        data.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.section_id;
                            option.textContent = section.section_name;
                            select.appendChild(option);
                        });
                    });
                })
                .catch(error => console.error('Error fetching sections:', error));
        }
    }

    // Initialize fields when modal is shown
    document.getElementById('manageScheduleModal').addEventListener('shown.bs.modal', function() {
        loadDepartments();
        loadYearLevels();
    });

    // let unsavedChanges = false;

    // // Function to mark unsaved changes
    // function markUnsaved() {
    //     unsavedChanges = true;
    // }

    // // Function to initialize change tracking on form fields
    // function trackChanges() {
    //     const elements = document.querySelectorAll('#manageScheduleModal input, #manageScheduleModal select');
        
    //     // Remove previous listeners before adding new ones
    //     elements.forEach(element => {
    //         element.removeEventListener('change', markUnsaved); // Ensure no duplicate listeners
    //         element.addEventListener('change', markUnsaved);    // Add listener once
    //     });
    // }

    // // Reset unsavedChanges flag when Save button is clicked
    // document.getElementById('saveScheduleBtn').addEventListener('click', function() {
    //     unsavedChanges = false; // Reset the flag on successful save
    //     saveSchedules(); // Call your function to save schedules here
    // });

    // // Prevent modal from closing if there are unsaved changes (attach this listener once)
    // function setupCloseListener() {
    //     document.getElementById('manageScheduleModal').addEventListener('hide.bs.modal', function(event) {
    //         if (unsavedChanges) {
    //             const confirmClose = confirm("You have unsaved changes. Are you sure you want to close?");
    //             if (!confirmClose) {
    //                 event.preventDefault(); // Prevent modal from closing
    //             }
    //         }
    //     });
    // }

    // // Attach close listener once during initial setup
    // setupCloseListener();

    // // Call trackChanges() to initialize change listeners only once per modal instance
    // document.getElementById('manageScheduleModal').addEventListener('shown.bs.modal', function() {
    //     unsavedChanges = false; // Reset unsavedChanges when the modal opens
    //     trackChanges(); // Initialize change tracking for all inputs in modal
    // });
    
</script> -->
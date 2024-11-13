<!-- Add Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* Container for each list item (department, section, subject) */
    .list-group-item {
        display: flex;
        justify-content: space-between;
        /* This spaces out the text and button */
        align-items: center;
    }

    /* Delete button style */
    .delete-btn {
        margin-left: auto;
        /* Push the button to the right */
        padding: 5px 10px;
        /* Optional: Adjust the padding */
    }

    /* Remove hover effect and background for the pen icon */
    #editBtn {
        background: transparent;
        /* Remove background */
        border: none;
        /* Remove border */
        outline: none;
        /* Remove outline on focus */
        padding: 0;
        /* Remove padding */
        cursor: pointer;
        /* Show pointer cursor on hover */
        transition: transform 0.1s ease;
        /* Add a smooth transition for click effect */
    }

    /* Click animation effect */
    #editBtn:active {
        transform: scale(0.65);
        /* Slightly shrink the icon on click */
    }

    /* Remove hover effect from the pen icon */
    #editBtn:hover {
        background: transparent;
        /* Remove background on hover */
        box-shadow: none;
        /* Remove any shadow on hover */
    }
</style>

<!-- Button to Manage Departments -->
<button class="btn btn-warning w-100 mt-2" id="manageDepartmentsBtn" data-bs-toggle="modal" data-bs-target="#manageDepartmentsModal">
    <i class="bi bi-building"></i> Manage Departments
</button>

<!-- Manage Departments Modal -->
<div class="modal fade" id="manageDepartmentsModal" tabindex="-1" aria-labelledby="manageDepartmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageDepartmentsModalLabel">Manage Departments</h5>
                <div id="editModeAlert" class="alert alert-warning d-none position-fixed top-0 start-50 translate-middle-x mt-3" role="alert">
                    Edit mode is enabled. Click the "<i class="fas fa-pen"></i>" icon again to disable.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Side: Available Records -->
                    <div class="col-md-6">
                        <!-- Add the pen icon to toggle delete buttons, leveled with Available Departments -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h6>Available Departments</h6>
                            <button id="editBtn" class="btn btn-light">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                        <ul id="available-departments" class="list-group">
                            <!-- Department records will be displayed here -->
                        </ul>
                        <h6 class="mt-4">Available Year Levels</h6>
                        <ul id="available-year-levels" class="list-group" style="max-height: 150px; overflow-y: auto;">
                            <!-- Year Level records will be displayed here -->
                        </ul>
                        <h6 class="mt-4">Available Sections</h6>
                        <ul id="available-sections" class="list-group" style="max-height: 150px; overflow-y: auto;">
                            <!-- Section records will be displayed here -->
                        </ul>
                        <h6 class="mt-4">Available Subjects</h6>
                        <ul id="available-subjects" class="list-group">
                            <!-- Subject records will be displayed here -->
                        </ul>
                    </div>

                    <!-- Right Side: Add New Records -->
                    <div class="col-md-6">
                        <!-- Add Department -->
                        <h6>Add New Department:</h6>
                        <form id="addDepartmentForm">
                            <div class="mb-3">
                                <label for="new_dep_name" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="new_dep_name" name="new_dep_name" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Department</button>
                        </form>

                        <hr>

                        <!-- Add Year Level -->
                        <h6>Add Year Level:</h6>
                        <form id="addYearLevelForm">
                            <div class="mb-3">
                                <label for="new_year_name" class="form-label">Year Level</label>
                                <input type="text" id="new_year_name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Year Level</button>
                        </form>

                        <hr>

                        <!-- Add Section -->
                        <h6>Add New Section:</h6>
                        <form id="addSectionForm">
                            <div class="mb-3">
                                <label for="new_section_name" class="form-label">Section Name</label>
                                <input type="text" class="form-control" id="new_section_name" name="new_section_name" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Section</button>
                        </form>

                        <hr>

                        <!-- Add Subject -->
                        <h6>Add New Subject:</h6>
                        <form id="addSubjectForm">
                            <div class="mb-3">
                                <label for="subject_dep_id" class="form-label">Department</label>
                                <select class="form-control" id="subject_dep_id" name="subject_dep_id" required>
                                    <option value="">Select Department</option>
                                    <!-- Department options populated here -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="subject_year_id" class="form-label">Year Level</label>
                                <select class="form-control" id="subject_year_id" name="subject_year_id" required>
                                    <option value="">Select Year Level</option>
                                    <!-- Year Level options populated here -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="new_sub_code" class="form-label">Subject Code</label>
                                <input type="text" class="form-control" id="new_sub_code" name="new_sub_code" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_sub_name" class="form-label">Subject Name</label>
                                <input type="text" class="form-control" id="new_sub_name" name="new_sub_name" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add Subject</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" id="close-btn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>


<script>
    // Function to reset a form and clear its fields manually
    function resetForm(formId) {
        const form = document.getElementById(formId);

        // Clear text input fields
        const inputFields = form.querySelectorAll('input[type="text"], input[type="number"], input[type="email"]');
        inputFields.forEach(input => {
            input.value = ''; // Clear the input field
        });

        // Clear select dropdowns and reset to the first option
        const selectElements = form.querySelectorAll('select');
        selectElements.forEach(select => {
            select.selectedIndex = 0; // Reset to the first option (usually a placeholder)
        });

        // Optionally, if there are any radio buttons or checkboxes, reset them
        const radioButtons = form.querySelectorAll('input[type="radio"], input[type="checkbox"]');
        radioButtons.forEach(radio => {
            radio.checked = false; // Uncheck radio buttons and checkboxes
        });
    }

    document.getElementById('manageDepartmentsBtn').addEventListener('click', function() {
        // Fetch available departments for dropdown in the subject form
        fetch('fetch_departments.php')
            .then(response => response.json())
            .then(data => {
                const departmentSelect = document.getElementById('subject_dep_id');
                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.dep_id;
                    option.textContent = department.dep_name;
                    departmentSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching departments:', error));

        // Fetch available departments for the left side list
        fetch('fetch_departments.php')
            .then(response => response.json())
            .then(data => {
                const departmentsList = document.getElementById('available-departments');
                departmentsList.innerHTML = '';

                data.forEach(department => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = department.dep_name;
                    departmentsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching departments:', error));

        // Fetch available sections for the left side list
        fetch('fetch_sections.php')
            .then(response => response.json())
            .then(data => {
                const sectionsList = document.getElementById('available-sections');
                sectionsList.innerHTML = '';

                data.forEach(section => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = section.section_name;
                    sectionsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching sections:', error));

        // Fetch available subjects for the left side list
        fetch('fetch_subjects.php')
            .then(response => response.json())
            .then(data => {
                const subjectsList = document.getElementById('available-subjects');
                subjectsList.innerHTML = '';

                data.forEach(subject => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `${subject.sub_code} - ${subject.sub_name}`;
                    subjectsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching subjects:', error));

        // Fetch available year levels for the left side list and for the year level select dropdown in the subject form
        fetch('fetch_year_levels.php')
            .then(response => response.json())
            .then(data => {
                const availableYearLevelsElement = document.getElementById('available-year-levels');
                const yearLevelSelect = document.getElementById('subject_year_id');

                yearLevelSelect.innerHTML = '<option value="">Select Year Level</option>';
                availableYearLevelsElement.innerHTML = '';

                data.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year.year_id;
                    option.textContent = year.year_name;
                    yearLevelSelect.appendChild(option);

                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = year.year_name;
                    availableYearLevelsElement.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching year levels:', error));
    });

    document.getElementById('addDepartmentForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the page from reloading

        const depName = document.getElementById('new_dep_name').value; // Get the department name

        fetch('add_department.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'new_dep_name': depName
                })
            })
            .then(response => response.text()) // Get response text from the server
            .then(data => {
                alert(data); // Show the response from PHP (success or error message)
                if (data.includes('successfully')) {
                    loadDepartments(); // Reload the department dropdown and list
                    resetForm('addDepartmentForm');
                }
            })
            .catch(error => console.error('Error:', error)); // Handle any errors
    });

    // Handle Adding a New Year Level
    document.getElementById('addYearLevelForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent page reload

        const yearName = document.getElementById('new_year_name').value; // Get the year level name

        // Send the new year level to the server
        fetch('add_year_level.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'new_year_name': yearName
                })
            })
            .then(response => response.text()) // Get the response from the server
            .then(data => {
                alert(data); // Show success or error message
                if (data.includes('successfully')) {
                    loadYearLevels(); // Refresh the year levels list
                    resetForm('addYearLevelForm'); // Reset the form for next input
                }
            })
            .catch(error => console.error('Error:', error)); // Handle any errors
    });

    document.getElementById('addSectionForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const sectionName = document.getElementById('new_section_name').value;

        fetch('add_section.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'new_section_name': sectionName
                })
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Show the response from PHP (success or error message)
                if (data.includes('successfully')) {
                    loadSections(); // Refresh the section list
                    resetForm('addSectionForm');
                }
            })
            .catch(error => console.error('Error:', error)); // Handle any errors
    });

    document.getElementById('addSubjectForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const depId = document.getElementById('subject_dep_id').value;
        const yearId = document.getElementById('subject_year_id').value;
        const subCode = document.getElementById('new_sub_code').value;
        const subName = document.getElementById('new_sub_name').value;

        fetch('add_subject.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'subject_dep_id': depId,
                    'subject_year_id': yearId,
                    'new_sub_code': subCode,
                    'new_sub_name': subName
                })
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Show the response from PHP (success or error message)
                if (data.includes('successfully')) {
                    loadSubjects(); // Refresh the subjects list
                    resetForm('addSubjectForm');
                }
            })
            .catch(error => console.error('Error:', error)); // Handle any errors
    });

    // Function to show/hide delete buttons
    function toggleDeleteButtons() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.classList.toggle('d-none'); // Toggle the visibility of delete buttons
        });
    }

    // Function to disable the forms
    function disableForms() {
        const forms = document.querySelectorAll('#addDepartmentForm, #addYearLevelForm, #addSectionForm, #addSubjectForm');
        forms.forEach(form => {
            const formElements = form.querySelectorAll('input, select, button');
            formElements.forEach(element => {
                element.disabled = true; // Disable each input, select, and button inside the form
            });
        });
    }

    // Function to enable the forms
    function enableForms() {
        const forms = document.querySelectorAll('#addDepartmentForm, #addYearLevelForm, #addSectionForm, #addSubjectForm');
        forms.forEach(form => {
            const formElements = form.querySelectorAll('input, select, button');
            formElements.forEach(element => {
                element.disabled = false; // Enable each input, select, and button inside the form
            });
        });
    }

    // Select the modal element by its ID
    const modalElement = document.getElementById('manageDepartmentsModal');
    const closeButton = modalElement.querySelector('.btn-close');
    const editModeAlert = document.getElementById('editModeAlert'); // Floating alert

    // Function to prevent modal from closing
    function preventModalClose(event) {
        event.preventDefault(); // Block the modal from hiding
        editModeAlert.classList.remove('d-none'); // Show the floating alert

        // Hide the alert after 3 seconds
        setTimeout(() => {
            editModeAlert.classList.add('d-none'); // Hide the floating alert
        }, 3000); // 3000 ms = 3 seconds
    }

    // Add event listener to the "Edit" button (pen button)
    document.getElementById('editBtn').addEventListener('click', function() {
        toggleDeleteButtons(); // Toggle delete buttons
        const firstInput = document.querySelector('#addDepartmentForm input');
        const isFormDisabled = firstInput.disabled;

        if (isFormDisabled) {
            enableForms(); // Enable the forms
            modalElement.removeEventListener('hide.bs.modal', preventModalClose); // Allow modal to close
            editModeAlert.classList.add('d-none'); // Hide the alert immediately if edit mode is turned off
        } else {
            disableForms(); // Disable the forms
            modalElement.addEventListener('hide.bs.modal', preventModalClose); // Prevent modal from closing
        }
    });

    // Prevent modal from closing when the X button is clicked in edit mode
    closeButton.addEventListener('click', function(event) {
        const firstInput = document.querySelector('#addDepartmentForm input');
        const isFormDisabled = firstInput.disabled;

        if (isFormDisabled) {
            event.preventDefault(); // Prevent modal close
            editModeAlert.classList.remove('d-none'); // Show the floating alert

            // Hide the alert after 3 seconds
            setTimeout(() => {
                editModeAlert.classList.add('d-none'); // Hide the floating alert
            }, 3000); // 3000 ms = 3 seconds
        }
    });

    // Add event listeners for delete buttons inside the 'Manage Departments' modal
    // Deleting a department
    function deleteDepartment(depId) {
        if (confirm('Are you sure you want to delete this department?')) {
            fetch('delete_department.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'dep_id': depId
                    })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes('successfully')) {
                        // Remove the department from the list after successful deletion
                        const departmentItem = document.querySelector(`button[data-id="${depId}"]`).closest('li');
                        departmentItem.remove();
                        refreshDepartments();
                    }
                })
                .catch(error => console.error('Error deleting department:', error));
        }
    }

    // Deleting a year level
    function deleteYearLevel(yearId) {
        if (confirm('Are you sure you want to delete this year level?')) {
            fetch('delete_year_level.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'year_id': yearId
                    })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes('successfully')) {
                        // Remove the year level from the list after successful deletion
                        const yearLevelItem = document.querySelector(`button[data-id="${yearId}"]`).closest('li');
                        yearLevelItem.remove();
                        refreshYearLevels();
                    }
                })
                .catch(error => console.error('Error deleting year level:', error));
        }
    }

    // Deleting a section
    function deleteSection(sectionId) {
        if (confirm('Are you sure you want to delete this section?')) {
            fetch('delete_section.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'section_id': sectionId
                    })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes('successfully')) {
                        // Remove the section from the list after successful deletion
                        const sectionItem = document.querySelector(`button[data-id="${sectionId}"]`).closest('li');
                        sectionItem.remove();
                    }
                })
                .catch(error => console.error('Error deleting section:', error));
        }
    }

    // Deleting a subject
    function deleteSubject(subId) {
        if (confirm('Are you sure you want to delete this subject?')) {
            fetch('delete_subject.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'sub_id': subId
                    })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes('successfully')) {
                        // Remove the subject from the list after successful deletion
                        const subjectItem = document.querySelector(`button[data-id="${subId}"]`).closest('li');
                        subjectItem.remove();
                    }
                })
                .catch(error => console.error('Error deleting subject:', error));
        }
    }

    // Fetch available departments when the 'Manage Departments' modal is opened
    document.getElementById('manageDepartmentsBtn').addEventListener('click', function() {
        // Fetch available departments
        fetch('fetch_departments.php')
            .then(response => response.json())
            .then(data => {
                const departmentsList = document.getElementById('available-departments');
                departmentsList.innerHTML = '';

                data.forEach(department => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = department.dep_name;

                    // Add delete button for each department (hidden by default)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', department.dep_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteDepartment(department.dep_id);
                    });
                    li.appendChild(deleteBtn);

                    departmentsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching departments:', error));

        //Fetch year levels
        fetch('fetch_year_levels.php')
            .then(response => response.json())
            .then(data => {
                const availableYearLevelsElement = document.getElementById('available-year-levels');
                const yearLevelSelect = document.getElementById('subject_year_id');

                yearLevelSelect.innerHTML = '<option value="">Select Year Level</option>';
                availableYearLevelsElement.innerHTML = ''; // Clear current list

                data.forEach(year => {
                    // Populate the select dropdown for adding subjects
                    const option = document.createElement('option');
                    option.value = year.year_id;
                    option.textContent = year.year_name;
                    yearLevelSelect.appendChild(option);

                    // Create the list item for displaying year levels
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = year.year_name;

                    // Create a delete button for each year level
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', year.year_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteYearLevel(year.year_id);
                    });

                    li.appendChild(deleteBtn);
                    availableYearLevelsElement.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching year levels:', error));

        // Fetch available sections when the 'Manage Sections' modal is opened
        fetch('fetch_sections.php')
            .then(response => response.json())
            .then(data => {
                const sectionsList = document.getElementById('available-sections');
                sectionsList.innerHTML = '';

                data.forEach(section => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = section.section_name;

                    // Add delete button for each section (hidden by default)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', section.section_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteSection(section.section_id);
                    });
                    li.appendChild(deleteBtn);

                    sectionsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching sections:', error));

        // Fetch available subjects when the 'Manage Subjects' modal is opened
        fetch('fetch_subjects.php')
            .then(response => response.json())
            .then(data => {
                const subjectsList = document.getElementById('available-subjects');
                subjectsList.innerHTML = '';

                data.forEach(subject => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `(${subject.dep_name} - ${subject.year_name} Year) | ${subject.sub_code} - ${subject.sub_name}`;

                    // Add delete button for each subject (hidden by default)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', subject.sub_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteSubject(subject.sub_id, subject.sub_name, subject.sub_code);
                    });
                    li.appendChild(deleteBtn);

                    subjectsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching subjects:', error));
    });

    // Function to refresh the department dropdown and list
    function loadDepartments() {
        fetch('fetch_departments.php')
            .then(response => response.json())
            .then(data => {
                const departmentSelect = document.getElementById('subject_dep_id');
                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.dep_id;
                    option.textContent = department.dep_name;
                    departmentSelect.appendChild(option);
                });

                const departmentsList = document.getElementById('available-departments');
                departmentsList.innerHTML = '';

                data.forEach(department => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = department.dep_name;


                    // Add delete button for each department (hidden by default)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', department.dep_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteDepartment(department.dep_id);
                    });
                    li.appendChild(deleteBtn);

                    departmentsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching departments:', error));
    }

    // Function to Load Year Levels Dynamically
    function loadYearLevels() {
        fetch('fetch_year_levels.php')
            .then(response => response.json())
            .then(data => {
                const yearSelect = document.getElementById('subject_year_id');
                yearSelect.innerHTML = '<option value="">Select Year Level</option>';

                data.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year.year_id;
                    option.textContent = year.year_name;
                    yearSelect.appendChild(option);
                });

                const availableYearLevelsElement = document.getElementById('available-year-levels');
                availableYearLevelsElement.innerHTML = ''; // Clear the current list

                data.forEach(year => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = year.year_name;

                    // Create a delete button for each year level
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', year.year_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteYearLevel(year.year_id);

                    });
                    li.appendChild(deleteBtn);

                    availableYearLevelsElement.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching year levels:', error));
    }

    // Function to refresh the section list
    function loadSections() {
        fetch('fetch_sections.php')
            .then(response => response.json())
            .then(data => {
                const sectionsList = document.getElementById('available-sections');
                sectionsList.innerHTML = '';

                data.forEach(section => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = section.section_name;


                    // Add delete button for each section (hidden by default)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', section.section_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteSection(section.section_id);
                    });
                    li.appendChild(deleteBtn);

                    sectionsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching sections:', error));
    }

    // Function to refresh the subjects list
    function loadSubjects() {
        fetch('fetch_subjects.php')
            .then(response => response.json())
            .then(data => {
                const subjectsList = document.getElementById('available-subjects');
                subjectsList.innerHTML = '';

                data.forEach(subject => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `(${subject.dep_name} - ${subject.year_name} Year) | ${subject.sub_code} - ${subject.sub_name}`;

                    // Add delete button for each subject (hidden by default)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2', 'd-none', 'delete-btn');
                    deleteBtn.setAttribute('data-id', subject.sub_id);
                    deleteBtn.addEventListener('click', function() {
                        deleteSubject(subject.sub_id);
                    });
                    li.appendChild(deleteBtn);

                    subjectsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching subjects:', error));
    }

    function refreshYearLevels() {
        fetch('fetch_year_levels.php')
            .then(response => response.json())
            .then(data => {
                const yearSelect = document.getElementById('subject_year_id');
                yearSelect.innerHTML = '<option value="">Select Year Level</option>';

                data.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year.year_id;
                    option.textContent = year.year_name;
                    yearSelect.appendChild(option);
                })
            })
    };

    function refreshDepartments() {
        fetch('fetch_departments.php')
            .then(response => response.json())
            .then(data => {
                const departmentSelect = document.getElementById('subject_dep_id');
                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.dep_id;
                    option.textContent = department.dep_name;
                    departmentSelect.appendChild(option);
                })
            })
    };
</script>
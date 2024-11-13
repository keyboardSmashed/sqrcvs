<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .table-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="table-container">
            <h2 class="text-center">Uploaded Student Records</h2>
            <table class="table table-hover table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Birthday</th>
                        <th>Gender</th>
                        <th>Contact No</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>ZIP</th>
                        <th>Street</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="record-body">
                    <!-- Records will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const records = JSON.parse(localStorage.getItem('uploadedRecords')) || [];
        const tbody = document.getElementById('record-body');

        records.forEach(record => {
            const row = document.createElement('tr');
            record.forEach((data, index) => {
                const cell = document.createElement('td');

                // Format the date if it's the birthday field (assuming it's at index 4)
                if (index === 4) {
                    const date = new Date(data);
                    cell.textContent = date.toISOString().split('T')[0]; // Format to YYYY-MM-DD
                } else {
                    cell.textContent = data;
                }
                
                row.appendChild(cell);
            });
            tbody.appendChild(row);
        });
    </script>
</body>
</html>

// Function to format date and time
function formatDateTime(dateTimeString) {
    const dateTime = new Date(dateTimeString);
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    const date = dateTime.toLocaleDateString(undefined, options);
    const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
    const time = dateTime.toLocaleTimeString(undefined, timeOptions);
    return { date, time };
}

// Function to get the scan type (with debug logging)
function getScanType(type) {
    console.log('Raw type:', type);  // Log the raw type value

    const numericType = Number(type);  // Ensure the type is treated as a number
    console.log('Converted type:', numericType);  // Log the converted type value

    if (numericType === 1) {
        return 'Entry';
    } else if (numericType === 2) {
        return 'Exit';
    } else {
        console.warn('Unknown type detected:', numericType);  // Warn if type is unknown
        return 'Unknown';
    }
}

// Function to populate scan logs
function populateScanLogs(scanLogs) {
    const scanLogsBody = document.getElementById('scan-logs-body');
    scanLogsBody.innerHTML = ''; // Clear existing logs

    scanLogs.forEach(log => {
        const { date, time } = formatDateTime(log['date-time']); // Separate date and time

        console.log('Log object:', log);  // Log the entire log object for debugging

        const row = `
            <tr>
                <td class="small">${log.stud_id}</td>
                <td class="small">${log.full_name}</td>
                <td class="small">${date}</td>
                <td class="small">${time}</td>
                <td class="small">
                    ${getScanType(log.type)}  <!-- Correct scan type display -->
                </td>
            </tr>
        `;
        scanLogsBody.innerHTML += row; // Append new row
    });
}

// Function to fetch scan logs
function fetchScanLogs() {
    fetch('fetch_scan_logs.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                populateScanLogs(data.data); // Call populate function with logs
            } else {
                console.error('Failed to fetch scan logs:', data.message);
            }
        })
        .catch(error => console.error('Error fetching scan logs:', error));
}

// Set interval to update logs every 1 seconds
setInterval(fetchScanLogs, 1000); // 1000 ms = 1 seconds

// Initial fetch when the page loads
fetchScanLogs();

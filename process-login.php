<?php
// Proceed with login attempt if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Include database connection
    $mysqli = require_once "../sqrcvs/db_conn.php";

    // Prepare SQL statement to select user based on username or email
    $sql = "SELECT * FROM admin_accounts WHERE admin_id = ? OR admin_email = ?";

    // Prepare the statement
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Preparation failed: " . $mysqli->error);
    }

    // Bind parameters (username or email)
    $uname = $_POST['username'];
    $stmt->bind_param("ss", $uname, $uname);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the user data
    $user = $result->fetch_assoc();

    // Check if user exists and password is correct
    if ($user && $_POST['password'] === $user["password"]) {
        // Start session
        session_start();

        // Store user data in session variables
        $_SESSION['admin_id'] = $user['admin_id'];
        $_SESSION['admin_email'] = $user['admin_email'];
        $_SESSION['first_name'] = $user['first_name_admin'];
        $_SESSION['mid_name'] = $user['mid_name_admin'];
        $_SESSION['last_name'] = $user['last_name_admin'];
        $_SESSION['admin_img'] = $user['admin_img'];

        // Redirect to admin dashboard upon successful login
        header("Location: ../sqrcvs/admin/admin_dash.php");
        exit();
    } else {
        // Redirect back to login page with error message and username
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        header("Location: index.php?errorMain=Failed to Login!&username=" . urlencode($username));
        exit();
    }
}
?>

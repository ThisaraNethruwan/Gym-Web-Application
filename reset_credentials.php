<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$action = $_POST['action'];
$message = '';
$status = '';
$account_type = $_SESSION['account_type'];

// Reset username
if ($action === 'reset_username') {
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);

    // Verify current password
    $verify_sql = "SELECT password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $verify_sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($current_password, $user['password'])) {
        // Check if new username already exists
        $check_sql = "SELECT id FROM users WHERE username = '$new_username' AND username != '$username'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) == 0) {
            // Update username
            $update_sql = "UPDATE users SET username = '$new_username' WHERE username = '$username'";
            if (mysqli_query($conn, $update_sql)) {
                $_SESSION['username'] = $new_username;
                $message = "Username updated successfully!";
                $status = "success";
            } else {
                $message = "Error updating username.";
                $status = "error";
            }
        } else {
            $message = "Username already exists.";
            $status = "error";
        }
    } else {
        $message = "Invalid current password.";
        $status = "error";
    }
    
    $_SESSION['username_message'] = $message;
    $_SESSION['username_status'] = $status;
}

// Reset password
if ($action === 'reset_password') {
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Verify current password
    $verify_sql = "SELECT password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $verify_sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
            
            if (mysqli_query($conn, $update_sql)) {
                $message = "Password updated successfully!";
                $status = "success";
            } else {
                $message = "Error updating password.";
                $status = "error";
            }
        } else {
            $message = "New passwords do not match.";
            $status = "error";
        }
    } else {
        $message = "Invalid current password.";
        $status = "error";
    }
    
    $_SESSION['password_message'] = $message;
    $_SESSION['password_status'] = $status;
}

mysqli_close($conn);


// Get account type from database
$conn = mysqli_connect("localhost", "root", "", "studentform");
$stmt = mysqli_prepare($conn, "SELECT account_type FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_close($conn);

if ($user['account_type'] === 'Admin') {
   header("Location: settings.php");
} else {
   header("Location: admin.php#settings");
}
exit;
?>
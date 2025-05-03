<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Welcome message
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>Welcome</title>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>Thank you for registering. We're glad to have you as part of our community.</p>
    <p>You can now explore our <a href="memberships.html">Membership Plans</a>, check out our <a href="activities.html">Activities</a>, or view our <a href="gallery.html">Gallery</a>.</p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

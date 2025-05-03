<?php
session_start();
$conn = new mysqli("localhost", "root", "", "studentform");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get reservation details from the form
$user_id = $_SESSION['user_id']; // Ensure user is logged in and user_id is set in session
$activity_id = $_POST['activity_id'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

// Insert reservation into the database
$sql = "INSERT INTO reserve (user_id, activity_id, start_time, end_time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $user_id, $activity_id, $start_time, $end_time);

if ($stmt->execute()) {
    echo "Reservation successful!";
    echo "<br><a href='activities.php'>Go back to activities</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

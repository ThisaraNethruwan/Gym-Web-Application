<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "studentform");

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $message_id = $_GET['id'];

    // Query to delete the message from the database
    $sql = "DELETE FROM user_messages WHERE id = $message_id";

    if (mysqli_query($conn, $sql)) {
        echo "Message deleted successfully.";
        // Redirect back to the staff panel
        header("Location: admin.php");
        exit;
    } else {
        echo "Error deleting message: " . mysqli_error($conn);
    }
} else {
    echo "No message ID provided.";
}

// Close the database connection
mysqli_close($conn);
?>

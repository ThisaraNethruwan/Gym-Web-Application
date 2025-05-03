<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .back-home {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    text-align: center;
}
.back-home:hover {
    background-color: #45a049;
}

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Submitted | Fitness Center</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

</head>
<body class="submit-message-page">

<?php
$conn = mysqli_connect("localhost", "root", "", "studentform");

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$message = htmlspecialchars(trim($_POST['message']));

// Insert data into 'user_messages' table
$sql = "INSERT INTO user_messages (name, email, message) VALUES ('$name', '$email', '$message')";
if (mysqli_query($conn, $sql)) {
    echo "<div class='message-container'>";
    echo "<div class='icon'>✅</div>";
    echo "<h2>Thank You!</h2>";
    echo "<p>Your message has been successfully submitted. We will get back to you as soon as possible.</p>";
    echo "<a href='index.html' class='back-home'>Return to Home</a>";
    echo "</div>";
} else {
    echo "ERROR: Hush! Sorry. $sql ." . mysqli_error($conn);
}
mysqli_close($conn);
?>

</body>
</html>

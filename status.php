<?php
// Check if a status message is set in the URL
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($status == 'success') {
    $message = "User deleted successfully.";
    $message_class = "success";
} elseif ($status == 'error') {
    $message = "Error deleting the user.";
    $message_class = "error";
} else {
    $message = "No status available.";
    $message_class = "info";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Message</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #ffffff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .greeting-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.575);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.562);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 1.5s ease-in-out;
        }

    

        p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1em;
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        .home-btn {
            text-decoration: none;
            padding: 10px 8px;
            background-color: #1b7906d5;
            color: white;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .home-btn:hover {
            background-color: #0c0e97;
        }

        .message {
            padding: 15px;
            font-size: 16px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background-color: white;
            color: black;
            font-size: 25px;
        }

        .error {
            background-color: #ff3333;
            color: white;
        }

        .info {
            background-color: #ffd700;
            color: black;
        }
    </style>
</head>
<body>

    <div class="greeting-container">
        
        <div class="message <?php echo $message_class; ?>">
            <?php echo $message; ?>
        </div>
        <p><a href="admin.php" class="home-btn">back to Dashboard</a></p>
    </div>

</body>
</html>

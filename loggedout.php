<?php
session_start(); // Start the session

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>Logging Out</title>
    <body class="bg-gray-900">
    <script>
        // Set a timeout to redirect to the homepage after 2 seconds
        setTimeout(function() {
            window.location.href = 'login.php'; // Change this to your homepage URL
        }, 3000);
    </script>
    <style>
        body {
            background-color:rgba(0, 0, 0, 0.88);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)), url('bg 002.png');
background-size: cover; 
background-position: center; 
background-repeat: no-repeat; 
  
            color: #fff;
            text-align: center;
            margin: 0;
        }
        .logout-container {
        
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            font-size: 28px;
            margin-bottom: 10px;
            color: white;
            letter-spacing: 5px;
        }
        p {
            font-size: 16px;
            color: #ddd;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>

    <div class="logout-container">
        <h2>.......You Have Successfully Logged Out.......</h2>
       
    </div>
</body>
</html>
<?php
// Ensure session is started only once
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username']; // Get the logged-in user's username

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "studentform");

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if delete_id is set in URL
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare statement to check if the blog belongs to the logged-in user
    $checkQuery = "SELECT * FROM blogs WHERE id = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    // Only bind the integer parameter, since $delete_id is an integer
    mysqli_stmt_bind_param($stmt, 'i', $delete_id); // 'i' stands for integer
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Proceed with deletion
        $deleteQuery = "DELETE FROM blogs WHERE id = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        // Bind the integer parameter for deletion
        mysqli_stmt_bind_param($stmt, 'i', $delete_id);
        if (mysqli_stmt_execute($stmt)) {
            $message = "Blog deleted successfully!";
            $message_class = 'success';
        } else {
            $message = "Error: " . mysqli_error($conn);
            $message_class = 'error';
        }
    } else {
        $message = "Error: You do not have permission to delete this blog.";
        $message_class = 'error';
    }

    mysqli_stmt_close($stmt);
} else {
    $message = "Error: Blog ID is missing.";
    $message_class = 'error';
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Deletion</title>
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
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.562);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 1.5s ease-in-out;
        }

        h1 {
            font-family: 'Poppins', sans-serif;
            letter-spacing: 5px;
            font-size: 1.5em;
            color: #2d2d2d;
            margin-bottom: 20px;
            font-weight: 300;
        }

        p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1em;
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        .home-btn {
            text-decoration: none;
            padding: 12px 25px;
            background-color: green;
            color: white;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .home-btn:hover {
            background-color: blue;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="greeting-container">
        <?php if(isset($message)): ?>
            <h1><?php echo $message; ?></h1>
            <p>
                <a href="admin.php#blogs-display" class="home-btn">Back</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>

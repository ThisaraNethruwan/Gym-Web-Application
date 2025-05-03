/* delete_activity.php */
<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "studentform");

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Check if the 'activity_id' parameter is passed in the URL
if (isset($_GET['activity_id'])) {
    // Sanitize the 'activity_id' input
    $activity_id = (int)$_GET['activity_id'];

    // Use a prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "DELETE FROM user_activities WHERE activity_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $activity_id);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the admin panel with a success message
            header("Location: admin.php?msg=ActivityDeleted");
            exit;
        } else {
            echo "Error deleting activity: " . mysqli_stmt_error($stmt);
        }
        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the statement: " . mysqli_error($conn);
    }
} else {
    echo "No Activity ID provided.";
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Deletion</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #ffffff, #f2f2f2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .message-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            text-decoration: none;
            padding: 12px 25px;
            background-color: green;
            color: white;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
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
    <div class="message-container">
        <h1><?php echo htmlspecialchars($message); ?></h1>
        <p>
            <a href="admin.php" class="btn">Back to Admin</a>
        </p>
    </div>
</body>
</html>
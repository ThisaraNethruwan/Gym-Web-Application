<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Validate the image ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $imageId = intval($_GET['id']);

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "studentform");

    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Fetch the image details from the database
    $sql = "SELECT image_name FROM gallery WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $imageId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $imageName = $row['image_name'];
        $imagePath = 'uploads/' . $imageName;

        // Delete the image file from the server
        if (file_exists($imagePath) && unlink($imagePath)) {
            // Delete the image record from the database
            $deleteSql = "DELETE FROM gallery WHERE id = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, "i", $imageId);
            if (mysqli_stmt_execute($deleteStmt)) {
                $message = "Image deleted successfully.";
            } else {
                $message = "Error deleting image from the database: " . mysqli_error($conn);
            }
        } else {
            $message = "Failed to delete the image file. It may not exist.";
        }
    } else {
        $message = "Image not found in the database.";
    }

    mysqli_close($conn);
} else {
    $message = "Invalid or missing image ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Deletion</title>
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

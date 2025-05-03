<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentform"; // Ensure this matches your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle account creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $account_type = $_POST['account_type'] ?? 'User';
    $username = $_POST['username'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $membership_plan = $_POST['membership_plan'] ?? 'Basic';
    $description = $_POST['description'] ?? '';

    // Validate required fields
    if (empty($username) || empty($email) || empty($password) || empty($gender)) {
        echo "Please fill in all required fields.";
        echo "<br><a href='account_details.php'>Back</a>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Handle profile image upload
    $upload_dir = "uploads/";
    $profile_image = $_FILES['profile_image'];
    $original_file_name = basename($profile_image['name']);
    $target_file = $upload_dir . $original_file_name;

    // Create the uploads folder if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Ensure unique file name in the uploads folder
    $file_counter = 1;
    while (file_exists($target_file)) {
        $file_info = pathinfo($original_file_name);
        $new_file_name = $file_info['filename'] . "_$file_counter." . $file_info['extension'];
        $target_file = $upload_dir . $new_file_name;
        $file_counter++;
    }

    // Validate and move the uploaded file
    if (move_uploaded_file($profile_image['tmp_name'], $target_file)) {
        // Store the actual file name in the database
        $stored_file_name = basename($target_file);

        // Prepare the SQL query
        $stmt = $conn->prepare(
            "INSERT INTO users (username, gender, email, password, membership_plan, account_type, description, profile_image) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        // Bind the parameters
        $stmt->bind_param(
            "ssssssss",
            $username,
            $gender,
            $email,
            $hashed_password,
            $membership_plan,
            $account_type,
            $description,
            $stored_file_name
        );

        // Execute the query and check the result
        if ($stmt->execute()) {
            
        } else {
            echo "Error saving to database: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to upload profile picture.";
        echo "<br><a href='account_details.php'>Back</a>";
    }
}

// Handle account deletion
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    // Retrieve the profile image file name from the database
    $query = "SELECT profile_image FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_image);
    $stmt->fetch();
    $stmt->close();

    // Delete the account and remove the profile image
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Remove the profile image file
        if (!empty($profile_image) && file_exists("uploads/" . $profile_image)) {
            unlink("uploads/" . $profile_image);
        }
        echo "upload_profile.php?delete_user=$user_id";
    } else {
        echo "Error deleting account: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>account create</title>
    <style>
    
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg,rgba(255, 255, 255, 0.72));
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
        <h1>Account Created Successfully</h1>
        <p>
            <a href="account_details.php" style="text-decoration: none; padding: 10px 45px; background-color: green; color: white; border-radius: 5px;">Back</a>
        </p>
    </div>
</body>
</html>
   


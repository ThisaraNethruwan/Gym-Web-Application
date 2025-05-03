<?php
include 'profile_header.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$username = htmlspecialchars($_SESSION['username']); // Sanitize the username

// Database connection
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "studentform";

$conn = mysqli_connect("localhost", "root", "", "studentform");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle activity application
if (isset($_POST['apply_activity'])) {
    $activity = $_POST['activity'];
    $stmt = $conn->prepare("INSERT INTO user_activities (username, activity) VALUES (?, ?)");
    $stmt->bind_param("ss", $_SESSION['username'], $activity);
    $stmt->execute();
    $stmt->close();

    // Also save activity to portfolio table
    $stmtPortfolio = $conn->prepare("INSERT INTO user_activities (username, activity) VALUES (?, ?)");
    $stmtPortfolio->bind_param("ss", $_SESSION['username'], $activity);
    $stmtPortfolio->execute();
    $stmtPortfolio->close();

    header("Location: dashboard.php");
    exit();
}

// Handle activity removal
if (isset($_POST['remove_activity'])) {
    $activity = $_POST['activity'];
    $stmt = $conn->prepare("DELETE FROM user_activities WHERE username = ? AND activity = ?");
    $stmt->bind_param("ss", $_SESSION['username'], $activity);
    $stmt->execute();
    $stmt->close();

    // Also remove activity from portfolio table
    $stmtPortfolio = $conn->prepare("DELETE FROM user_activities WHERE username = ? AND activity = ?");
    $stmtPortfolio->bind_param("ss", $_SESSION['username'], $activity);
    $stmtPortfolio->execute();
    $stmtPortfolio->close();

    header("Location: portfolio.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 20px;

            color: #333;
        }

        .portfolio-container {
            max-width: 900px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color:rgb(255, 255, 255);
        }
        h3 {
            text-align: left;
            color: black;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
        }

        .activity-item i {
            margin-right: 10px;
            color: green;
            
        }

        .remove-btn {
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            padding: 3px 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-btn:hover {
            background-color: #d93636;
        }

      
        .main-content {
            margin-left: 290px;
            padding: 20px;
        }

      
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #ffffff;
        }
        .main-content h1 {
            font-size: 40px;
            letter-spacing: 5px;
            font-family: 'Poppins', sans-serif;
            color: black;
           
        }
        .main-content p{
            letter-spacing: 8px;
            font-family: 'Poppins', sans-serif;
            color: black;
            font-size: 20px;
           
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 0px;
        
        }
        .portfolio-image {
            margin-left: 300px;
        }
     
       
    </style>
</head>

    <?php
    // Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}



// Fetch the logged-in user's details
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}


?>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


    <!-- Profile Header -->
    <div class="fixed top-4 right-1 z-10">
        <div class="flex items-center gap-3 bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
                <img src="<?php echo htmlspecialchars($user['profile_image'] ?: 'uploads/default-profile.png'); ?>" 
                     alt="Profile Image" 
                     class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">
                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full"></div>
            </div>
            <div class="pr-2">
                <p1 class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($user['username']); ?></p1><br>
                <p1 class="text-sm text-blue-700">Online</p1>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
    <div class="main-content">
        <div class="portfolio-container">
            <h3 class="text-2xl font-bold bg-clip-text text-transparent bg-gray-600">Your Activities</h3><br>
            <?php
            // Retrieve user activities from the portfolio table
            $stmt = $conn->prepare("SELECT activity FROM user_activities WHERE username = ?");
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='activity-item'>";
                    echo "<i class='fas fa-check-circle'></i>";
                    echo "<span>" . htmlspecialchars($row['activity']) . "</span>";
                    echo "<form method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='activity' value='" . htmlspecialchars($row['activity']) . "'>";
                    echo "<button type='submit' name='remove_activity' class='remove-btn'>Remove</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No activities applied yet.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>

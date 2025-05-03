<?php


include 'profile_header.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Sanitize the username from the session
$username = htmlspecialchars($_SESSION['username']);

// Database connection
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "studentform";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle activity selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activity_id'])) {
    $activityId = intval($_POST['activity_id']);

    // Fetch activity title from activities table
    $stmt = $conn->prepare("SELECT title FROM activities WHERE id = ?");
    $stmt->bind_param("i", $activityId);
    $stmt->execute();
    $result = $stmt->get_result();
    $activity = $result->fetch_assoc()['title'];
    $stmt->close();

    // Insert the selected activity into user_activities
    $insertStmt = $conn->prepare("INSERT INTO user_activities (username, activity) VALUES (?, ?)");
    $insertStmt->bind_param("ss", $username, $activity);
    $insertStmt->execute();
    $insertStmt->close();

    // Show success message
    echo "<script>alert('Activity \"$activity\" successfully applied!');</script>";
}

// Fetch all activities
$sql = "SELECT id, title, description, media_type, media_path FROM activities";
$activitiesResult = $conn->query($sql);

// Fetch the logged-in user's details
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();

// Check if user exists
if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            
            margin: 0;
            color: black;
        }

     
        .main-content {
            margin-left: 290px;
            padding: 20px;
        }

       
    </style>
</head>

<body>
    <!-- Sidebar -->
  

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

    <div class="main-content">
        <br><h2 class="text-4xl font-semibold text-center text-gray-600">Available Activities</h2>
        <section class="activities-section py-16">
            <div class="activities-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4">
                <?php while ($row = $activitiesResult->fetch_assoc()) : ?>
                    <div class="activity-description bg-white shadow-lg rounded-lg overflow-hidden hover:scale-105 transition-all duration-300 ease-in-out transform">
                        <?php if ($row['media_type'] === 'video') : ?>
                            <video autoplay muted loop class="w-full h-48 object-cover">
                                <source src="<?php echo htmlspecialchars($row['media_path']); ?>" type="video/mp4">
                            </video>
                        <?php elseif ($row['media_type'] === 'image') : ?>
                            <img src="<?php echo htmlspecialchars($row['media_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-48 object-cover">
                        <?php endif; ?>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($row['description']); ?></p>
                            <form method="POST">
                                <input type="hidden" name="activity_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="w-full bg-blue-700 text-white py-2 px-4 rounded-md hover:bg-blue-900 transition duration-200 ease-in-out">
                                    Select
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>
</body>
</html>

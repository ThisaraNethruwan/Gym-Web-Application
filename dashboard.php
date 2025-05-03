<?php
session_start(); // Start the session



// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$username = htmlspecialchars($_SESSION['username']); // Sanitize the username



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <title>User Dashboard</title>
    <style>
        body.dashboard-page {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
        }
     

        .sidebar {
            width: 290px;
            background-color: #1e3a8a;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color:rgb(30, 71, 182);
        }

        .main-content {
            margin-left: 290px;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
            margin-top: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #ffffff;
        }
        .main-content h1 {
            font-size: 35px;
            letter-spacing: 5px;
            font-family: 'Poppins', sans-serif;
            color: black;
           
        }
        .main-content p{
            letter-spacing: 5px;
            font-family: 'Poppins', sans-serif;
            color: black;
            font-size: 18px;
           
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
<body class="dashboard-page">
    <div class="sidebar">
        <h2><?php echo $username; ?></h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="activities.php" class="active">Activities</a>
        <a href="portfolio.php">Your Portfolio</a>
        
        <a href="user_details_cards.php">Your Account</a>
        
        <a href="loggedout.php">Logout</a>
       
    </div>


    <div class="main-content">
        <div class="welcome-section">
            <h1>Welcome <?php echo $username; ?></h1>
            <p>.....Manage your profile and activities here.....</p>
        </div>
        <div class="portfolio-image">
                   
                   <img src="portimg.avif" alt="">
      </div>
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

mysqli_close($conn);
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
                <p2 class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($user['username']); ?></p2><br>
                <p1 class="text-sm text-blue-700">Online</p1>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .modal {
            transition: opacity 0.3s ease-in-out;
            backdrop-filter: blur(5px);
        }
        .input-focus-effect:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        .profile-image-container {
        display: flex; /* Use flexbox to align content */
        justify-content: center; /* Center the image horizontally */
        align-items: center; /* Center the image vertically */
        margin-bottom: 10px; /* Space between the profile image and text below */
    }

    .profile-image {
        width: 130px; /* Adjust the size of the circular image */
        height: 130px; /* Keep the width and height equal to maintain the circular shape */
        border-radius: 50%; /* Makes the image circular */
        object-fit: cover; /* Ensures the image covers the circle area without distortion */
        border: 3px solid #fff; /* Optional: Adds a white border around the image */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.32); /* Optional: Adds a subtle shadow around the image */
    }
    </style>
</head>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];

$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


$username = $_SESSION['username']; // Get the logged-in username

// Query to fetch the profile image for the logged-in user
$sql = "SELECT profile_image FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

// Check if the user exists and fetch the profile image
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profile_image = $row['profile_image'];
} else {
    $profile_image = ''; // Set to empty if no profile image is found
}

mysqli_close($conn); // Close the database connection
?>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed h-full w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white">
        <div class="p-6">
            <!-- Profile Image Container Above Username -->
            <div class="profile-image-container">
                <?php
                    // Display the profile image if it exists
                    if (!empty($profile_image)) {
                        echo '<img src="uploads/' . htmlspecialchars($profile_image) . '" alt="Profile Image" class="profile-image">';
                    } else {
                        // If no profile image, show a default placeholder image
                        echo '<img src="uploads/accounticon.jpg" alt="Profile Image" class="profile-image">';
                    }
                ?>
            </div>
            <h2 class="text-2xl font-bold mb-8 text-center"><?php echo htmlspecialchars($username); ?></h2>

            <div class="space-y-4">
                <a href="adminmain.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="account_details.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-users mr-3"></i>Accounts
                </a>
                <a href="settings.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-users mr-3"></i>Settings
                </a>
                <a href="http://localhost/fitness/Employee-Attendance-Management-System/employee-attendance-management/admin/index.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all" aria-label="Go to EMS System">
                    <i class="fas fa-users mr-3"></i>EMS System
                </a>
                <a href="loggedout.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </div>
        </div>
    </div>
</body>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <!-- Main Content -->
    <div class="ml-64 p-6">
        <h1 class="text-3xl font-bold mb-6">Account Settings</h1>

      <!-- Settings Section -->
<section id="settings" class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto my-8">
    <h2 class="text-2xl font-bold mb-6">Account Settings</h2>

    <!-- Username Reset Form -->
    <div class="mb-8 p-6 bg-gray-50 rounded-lg">
        <h3 class="text-xl font-semibold mb-4">Reset Username</h3>
        <?php if (isset($_SESSION['username_message'])): ?>
            <div class="<?php echo $_SESSION['username_status'] === 'success' 
                ? 'bg-green-100 text-green-700 border-green-400' 
                : 'bg-red-100 text-red-700 border-red-400'; ?> 
                p-4 mb-4 rounded-lg border">
                <?php 
                echo $_SESSION['username_message'];
                unset($_SESSION['username_message']);
                unset($_SESSION['username_status']);
                ?>
            </div>
        <?php endif; ?>
        <form action="reset_credentials.php" method="POST" class="space-y-4">
            <div>
                <label for="new_username" class="block text-sm font-medium text-gray-700 mb-1">New Username</label>
                <input type="text" id="new_username" name="new_username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <div>
                <label for="current_password_username" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" id="current_password_username" name="current_password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <input type="hidden" name="action" value="reset_username">
            <button type="submit" 
                class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                Update Username
            </button>
        </form>
    </div>

    <!-- Password Reset Form -->
    <div class="p-6 bg-gray-50 rounded-lg">
        <h3 class="text-xl font-semibold mb-4">Reset Password</h3>
        <?php if (isset($_SESSION['password_message'])): ?>
            <div class="<?php echo $_SESSION['password_status'] === 'success' 
                ? 'bg-green-100 text-green-700 border-green-400' 
                : 'bg-red-100 text-red-700 border-red-400'; ?> 
                p-4 mb-4 rounded-lg border">
                <?php 
                echo $_SESSION['password_message'];
                unset($_SESSION['password_message']);
                unset($_SESSION['password_status']);
                ?>
            </div>
        <?php endif; ?>
        <form action="reset_credentials.php" method="POST" class="space-y-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" id="current_password" name="current_password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" id="new_password" name="new_password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <input type="hidden" name="action" value="reset_password">
            <button type="submit" 
                class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                Update Password
            </button>
        </form>
    </div>
</section>
    </div>

</body>
</html>


<?php
session_start();

include 'admin_sidebar.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<style>
              .section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: rgba(195, 196, 199, 0.81);;
            font-weight: 600;
            color: black;
        }
        table td {
            background-color: #fff;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .gallery-item {
            position: relative;
            width: 200px;
            height: 200px;
            overflow: hidden;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
          
        }
        .gallery-item a {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            text-decoration: none;
            border-radius: 3px;
        }
        #image-management button {
    background-color: blue;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: transform 0.3s ease;
}

#image-management button:hover {
    transform: scale(1.1); /* Slight zoom effect */
}

#image-management button:active {
    transform: scale(0.95); /* Shrink effect on click */
}
/* General body styles */
/* General body styles */
body {
    background-color: #f7fafc; /* bg-gray-100 */
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

/* Container for the entire layout */
.container {
    display: flex;
    min-height: 100vh;
}


    </style>
<?php
// Connect to the database
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
<!-- Make sure you include Tailwind CSS in your project -->
<!-- Overlay prevention layer -->
<div class="fixed top-0 right-0 w-32 h-20  z-[9999]"></div>

<!-- Profile Header -->
<div class="fixed top-4 right-4 z-[10000]">
    <div class="flex items-center gap-3 bg-white rounded-lg shadow-md p-2 hover:shadow-lg transition-shadow duration-300">
        <div class="relative">
            <?php
            if (!empty($profile_image)) {
                echo '<img src="uploads/' . htmlspecialchars($profile_image) . '" 
                    alt="Profile Image" 
                    class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">';
            } else {
                echo '<img src="uploads/default-profile.png" 
                    alt="Profile Image" 
                    class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">';
            }
            ?>
            <!-- Online status indicator -->
            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full"></div>
        </div>
        <div class="pr-2">
            <p class="text-1sm text-gray-800"><?php echo htmlspecialchars($username); ?></p>
            <p class="text-xs text-blue-700">Online</p>
        </div>
    </div>
</div>

        <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentform";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get total users with account_type = 'User'
    $totalUsers = $conn->query("
        SELECT COUNT(*) as count 
        FROM users 
        WHERE account_type = 'User'
    ")->fetch()['count'];
    
    // Get new users (registered within last 2 days) with account_type = 'User'
    $newUsers = $conn->query("
        SELECT COUNT(*) as count 
        FROM users 
        WHERE account_type = 'User' 
        AND created_at >= NOW() - INTERVAL 2 DAY
    ")->fetch()['count'];
    
    // Get total user activities
    $activities = $conn->query("SELECT COUNT(*) as count FROM user_activities")->fetch()['count'];
    $totalImages = $conn->query("SELECT COUNT(*) as count FROM gallery")->fetch()['count'];
    $totalBlogs = $conn->query("SELECT COUNT(*) as count FROM blogs")->fetch()['count'];
    
    // Get total messages
    $messages = $conn->query("SELECT COUNT(*) as count FROM user_messages")->fetch()['count'];
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<body class="bg-gray-100">
    <main class="flex-1 p-4 space-y-4">
        <!-- Overview Section -->
        <section id="overview" class="bg-white p-6 rounded-lg shadow">
   <h2><b>Overview</b></h2><br>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- User Activity -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <svg class="transform -rotate-90 w-full h-full">
                    <circle cx="64" cy="64" r="60" stroke="#E5E7EB" stroke-width="8" fill="none" />
                    <circle cx="64" cy="64" r="60" 
                            stroke="#22C55E"
                            stroke-width="8"
                            fill="none"
                            stroke-dasharray="377"
                            stroke-dashoffset="94"
                            class="transition-all duration-1000 ease-out">
                        <animate attributeName="stroke-dashoffset" from="377" to="94" dur="1s" fill="freeze" />
                    </circle>
                </svg>
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-3xl font-bold">
                    <?php echo htmlspecialchars($activities); ?>
                </span>
            </div>
            <h3 class="text-center text-lg font-bold">User Activity</h3>
            <p class="text-center text-sm text-gray-600 mt-2">Total User Activity</p>
        </div>

        <!-- Messages -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <svg class="transform -rotate-90 w-full h-full">
                    <circle cx="64" cy="64" r="60" stroke="#E5E7EB" stroke-width="8" fill="none" />
                    <circle cx="64" cy="64" r="60"
                            stroke="#EAB308"
                            stroke-width="8"
                            fill="none"
                            stroke-dasharray="377"
                            stroke-dashoffset="188"
                            class="transition-all duration-1000 ease-out">
                        <animate attributeName="stroke-dashoffset" from="377" to="188" dur="1s" fill="freeze" />
                    </circle>
                </svg>
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-3xl font-bold">
                    <?php echo htmlspecialchars($messages); ?>
                </span>
            </div>
            <h3 class="text-center text-lg font-bold">Messages</h3>
            <p class="text-center text-sm text-gray-600 mt-2">Total Messages</p>
        </div>

        <!-- New Users -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <svg class="transform -rotate-90 w-full h-full">
                    <circle cx="64" cy="64" r="60" stroke="#E5E7EB" stroke-width="8" fill="none" />
                    <circle cx="64" cy="64" r="60"
                            stroke="#EF4444"
                            stroke-width="8"
                            fill="none"
                            stroke-dasharray="377"
                            stroke-dashoffset="282"
                            class="transition-all duration-1000 ease-out">
                        <animate attributeName="stroke-dashoffset" from="377" to="282" dur="1s" fill="freeze" />
                    </circle>
                </svg>
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-3xl font-bold">
                    <?php echo htmlspecialchars($newUsers); ?>
                </span>
            </div>
            <h3 class="text-center text-lg font-bold">New Users</h3>
            <p class="text-center text-sm text-gray-600 mt-2">Regular users in last 2 days</p>
        </div>

        <!-- Total Users -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <svg class="transform -rotate-90 w-full h-full">
                    <circle cx="64" cy="64" r="60" stroke="#E5E7EB" stroke-width="8" fill="none" />
                    <circle cx="64" cy="64" r="60"
                            stroke="#3B82F6"
                            stroke-width="8"
                            fill="none"
                            stroke-dasharray="377"
                            stroke-dashoffset="188"
                            class="transition-all duration-1000 ease-out">
                        <animate attributeName="stroke-dashoffset" from="377" to="188" dur="1s" fill="freeze" />
                    </circle>
                </svg>
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-3xl font-bold">
                    <?php echo htmlspecialchars($totalUsers); ?>
                </span>
            </div>
            <h3 class="text-center text-lg font-bold">Total Users</h3>
            <p class="text-center text-sm text-gray-600 mt-2">Total regular users</p>
        </div>

        <!-- Gallery Images -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <svg class="transform -rotate-90 w-full h-full">
                    <circle cx="64" cy="64" r="60" stroke="#E5E7EB" stroke-width="8" fill="none" />
                    <circle cx="64" cy="64" r="60"
                            stroke="#06B6D4"
                            stroke-width="8"
                            fill="none"
                            stroke-dasharray="377"
                            stroke-dashoffset="150"
                            class="transition-all duration-1000 ease-out">
                        <animate attributeName="stroke-dashoffset" from="377" to="150" dur="1s" fill="freeze" />
                    </circle>
                </svg>
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-3xl font-bold">
                    <?php echo htmlspecialchars($totalImages); ?>
                </span>
            </div>
            <h3 class="text-center text-lg font-bold">Gallery Images</h3>
            <p class="text-center text-sm text-gray-600 mt-2">Total Gallery Images</p>
        </div>

        <!-- Blogs -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <svg class="transform -rotate-90 w-full h-full">
                    <circle cx="64" cy="64" r="60" stroke="#E5E7EB" stroke-width="8" fill="none" />
                    <circle cx="64" cy="64" r="60"
                            stroke="#8B5CF6"
                            stroke-width="8"
                            fill="none"
                            stroke-dasharray="377"
                            stroke-dashoffset="220"
                            class="transition-all duration-1000 ease-out">
                        <animate attributeName="stroke-dashoffset" from="377" to="220" dur="1s" fill="freeze" />
                    </circle>
                </svg>
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-3xl font-bold">
                    <?php echo htmlspecialchars($totalBlogs); ?>
                </span>
            </div>
            <h3 class="text-center text-lg font-bold">Blogs</h3>
            <p class="text-center text-sm text-gray-600 mt-2">Total Blogs</p>
        </div>
    </div>
</section>
        
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
<section id="messages" class=" p-6 rounded-lg shadow">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <style>
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .message-card {
            animation: slideIn 0.3s ease-out forwards;
        }
    </style>
</head>
<body class=" min-h-screen">
    <!-- Main Content - With space for existing sidebar -->
    <div class="ml-54 p-8"> <!-- ml-64 reserves space for your existing sidebar -->
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Inbox Messages</h2>
             
            </div>
            <div class="flex gap-4">
            <button id="filterBtn" 
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-full hover:bg-purple-700 transition-all"
                >
                    <span>Sort</span>
                    <svg id="sortIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
</button>

                <!-- Search Input -->
                <div class="relative">
                    <input type="text" id="searchInput01" placeholder="Search by name..." 
                           class="px-4 py-2 bg-white rounded-xl shadow-md hover:shadow-md transition-all w-64 focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>
        </div>
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
        <!-- Message Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6" id="messageGrid">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "studentform");

            if ($conn === false) {
                die("<div class='col-span-3 p-6 bg-red-50 text-red-500 rounded-xl'>ERROR: Could not connect. " . mysqli_connect_error() . "</div>");
            }

            $sql = "SELECT * FROM user_messages ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $initial = strtoupper(substr($row['name'], 0, 1));
                    $colors = ['from-blue-500 to-cyan-500', 'from-purple-500 to-pink-500', 'from-amber-500 to-orange-500', 'from-emerald-500 to-teal-500'];
                    $randomColor = $colors[array_rand($colors)];
            ?>
                    <div class="message-card bg-white border border-purple-300 rounded-xl shadow-sm hover:shadow-md transition-all p-6  data-name="<?php echo strtolower(htmlspecialchars($row['name'])); ?>">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-r <?php echo $randomColor; ?> flex items-center justify-center text-white font-bold text-xl">
                                    <?php echo $initial; ?>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['name']); ?></h3>
                                    <p class="text-sm text-gray-500"><?php echo $row['email']; ?></p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-400">ID: <?php echo $row['id']; ?></div>
                        </div>
                        
                        <div class="mb-6">
                            <p class="text-gray-600 line-clamp-3"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        </div>
                        
                        <div class="flex justify-end items-center">
                            <div class="flex gap-3">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($row['email']); ?>&su=Reply to User Message from Admin&body=Hi <?php echo urlencode($row['name']); ?>,%0D%0A%0D%0A<?php echo urlencode($row['message']); ?>" 
                                   target="_blank" 
                                   class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-violet-500 to-purple-500 text-white rounded-lg hover:text-white transition-all">
            <i class="fas fa-reply"></i>
            <span>Reply</span>
                                </a>
                                <a href="delete_message.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this message?')"
                                   class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-all">
                                    <i class="fas fa-trash-alt"></i>
                                    <span>Delete</span>
                                </a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<div class='col-span-3 p-6 bg-gray-50 text-gray-500 rounded-xl text-center'>No messages found</div>";
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput01 = document.getElementById('searchInput01');
        const messageCards = document.querySelectorAll('.message-card');

        searchInput01.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            messageCards.forEach(card => {
                const name = card.dataset.name;
                if (name.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

    // Sort functionality
let isSorted = false; // Track sorting state
let originalOrder = []; // Store the original order of cards

document.getElementById('filterBtn').addEventListener('click', function () {
    const messageGrid = document.getElementById('messageGrid');
    const cards = Array.from(messageGrid.children);

    if (!originalOrder.length) {
        // Save the original order of cards
        originalOrder = cards.slice();
    }

    if (!isSorted) {
        // Sort A to Z
        cards.sort((a, b) => {
            const nameA = a.dataset.name.toLowerCase();
            const nameB = b.dataset.name.toLowerCase();
            return nameA.localeCompare(nameB);
        });
        isSorted = true;
    } else {
        // Restore the original order
        cards.splice(0, cards.length, ...originalOrder);
        isSorted = false;
    }

    // Update the grid with the sorted/restored cards
    messageGrid.innerHTML = '';
    cards.forEach(card => messageGrid.appendChild(card));
});

    </script>
</body>
</section>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- First, add this search and sort section -->
<section id="user-management" class=" min-h-screen">
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-black">User Details</h2>
            
            <div class="flex gap-4">
                <!-- Search Box -->
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchInput01" 
                        placeholder="Search users..."
                        class="pl-10 pr-4 py-2 rounded-full border-2 border-purple-300 focus:border-purple-500 focus:outline-none w-64 transition-all"
                    >
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Sort Dropdown and Button -->
                <div class="flex items-center gap-2">
                <select id="sortType" class="block w-full px-4 py-2 text-sm rounded-full border border-purple-300 bg-white shadow-md focus:ring focus:ring-purple-500 focus:outline-none transition-all">
                    <option value="AtoZ" class="text-gray-600 hover:text-purple-600">Sort A to Z</option>
                    <option value="NewestToOldest" class="text-gray-600 hover:text-purple-600">Newest to Oldest</option>
                    </select>
                    <button 
                        id="sortButton"
                        class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-full hover:bg-purple-700 transition-all"
                    >
                        <span>Sort</span>
                        <svg id="sortIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    <?php
     

        // Database connection
$conn = mysqli_connect("localhost", "root", "", "studentform");

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$sql = "SELECT * FROM users WHERE account_type NOT IN ('admin', 'staff') ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<div id="userGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-1">';
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Assigning random gradient for user card background
        $gradients = [
            'from-purple-500 to-pink-500',
            'from-blue-500 to-teal-500',
            'from-green-500 to-teal-500',
            'from-orange-500 to-red-500',
            'from-pink-500 to-rose-500'
        ];
        $randomGradient = $gradients[array_rand($gradients)];

        $user = $row;
        
        // Fetch user profile image, fallback to a default image if none exists
        $profileImage = $row['profile_image'] ? "uploads/" . htmlspecialchars($row['profile_image']) : "accounticon.jpg";
        ?>
<div class="user-card transform hover:scale-105 transition-all duration-300" data-username="<?php echo htmlspecialchars(strtolower($row['username'])); ?>" data-created-at="<?php echo htmlspecialchars($row['created_at']); ?>">
    <div class="relative bg-gradient-to-r <?php echo $randomGradient; ?> border border-purple-300 rounded-2xl shadow-xl overflow-hidden">
        <div class="h-3"></div>

        <div class="p-6">
            <div class="relative mx-auto w-24 h-24 mb-4">
                <img src="<?php echo htmlspecialchars($row['profile_image'] ?: 'uploads/default-profile.png'); ?>" 
                     alt="Profile Image" 
                     class="w-full h-full object-cover rounded-full border-4 border-purple-100">
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-4 border-white bg-gradient-to-r <?php echo $randomGradient; ?>"></div>
            </div>

            <div class="text-center mb-4">
                <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($row['username']); ?></h3>
            </div>

            <div class="bg-gray-50 -mx-6 px-6 py-4">
                <div class="flex flex-col space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Plan</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r <?php echo $randomGradient; ?> text-white">
                            <?php echo htmlspecialchars($row['membership_plan']); ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Gender</span>
                        <span class="font-medium text-gray-800"><?php echo htmlspecialchars($row['gender']); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Address</span>
                        <span class="font-medium text-gray-800"><?php echo htmlspecialchars($row['address']); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium text-gray-800"><?php echo htmlspecialchars($row['email']); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Date of Birth</span>
                        <span class="font-medium text-gray-800"><?php echo htmlspecialchars($row['dob']); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Member Since</span>
                        <span class="font-medium text-gray-800"><?php echo htmlspecialchars($row['created_at']); ?></span>
                    </div>
                </div>

                <div class="mt-6 bg-white">
                    <a href="delete_users.php?id=<?php echo $row['id']; ?>" 
                       class="block w-full py-2 text-center rounded-full bg-red-500 text-white hover:bg-red-800 hover:text-white transition-colors"
                       onclick="return confirmDelete();">
                       
                        Delete Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

        
        <?php
    }
    echo '</div>';
} else {
    echo "<p>No users found.</p>";
}


?>

<script>
    function confirmDelete() {
        // Display confirmation dialog
        if (confirm("Are you sure you want to delete this account?")) {
            // If confirmed, display success toast message
            toastr.success('Account deleted successfully!');
            
            // Redirect to the delete_users.php page
            window.location.href = 'admin.php#user-management?id=<?php echo $row["id"]; ?>';
            
            return true; // Proceed with the redirection
        } else {
            // If canceled, prevent the default action
            return false;
        }
    }
</script>
                            </div>
                        </div>
                    </div>


        </div>
    </div>


<script>
   
    const sortButton01 = document.querySelector('#sortButton');
    const sortType = document.querySelector('#sortType');
    const userCards = document.querySelectorAll('.user-card');

    // Search functionality
  

      
    // Sort functionality
    sortButton.addEventListener('click', function() {
        const cards = Array.from(document.querySelectorAll('.user-card'));
        const isAscending = sortType.value === 'AtoZ';

        if (sortType.value === 'AtoZ') {
            cards.sort((a, b) => {
                return a.dataset.username.localeCompare(b.dataset.username);
            });
        } else if (sortType.value === 'NewestToOldest') {
            cards.sort((a, b) => {
                return new Date(b.dataset.createdAt) - new Date(a.dataset.createdAt);
            });
        }

        cards.forEach(card => userGrid.appendChild(card));
    });
</script>
</section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<section id="user-activities">
    <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-black">User Activities</h2>

    <!-- Top Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Activities -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600  rounded-xl p-4 text-white shadow-lgtransform transition-transform duration-300 hover:scale-105 hover:shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100">Total Activities</p>
                    <h3 class="text-2xl font-bold mt-1" id="totalActivities">0</h3>
                </div>
                <div class="bg-purple-400 bg-opacity-40 rounded-full p-3 ">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Today -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100">Active Today</p>
                    <h3 class="text-2xl font-bold mt-1" id="activeToday">0</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-40 rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- All Time Users -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 text-white shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100">Total Users</p>
                    <h3 class="text-2xl font-bold mt-1" id="totalUsers">0</h3>
                </div>
                <div class="bg-emerald-400 bg-opacity-40 rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Activity Rate -->
        <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl p-4 text-white shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-xl w-full max-w-md mx-auto">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-rose-100">Activity Rate</p>
                    <h3 class="text-2xl font-bold mt-1">85%</h3>
                </div>
                <div class="bg-rose-400 bg-opacity-40 rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Feed with Search and Sorting -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-4 border-b border-gray-100">
            <!-- Search Bar -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                           placeholder="Search by username or activity...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Filter Buttons -->
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Recent Activities</h2>
                <div class="flex gap-2">
                    <button onclick="filterActivities('today')" 
                            class="filter-btn px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors"
                            data-filter="today">
                        Today
                    </button>
                    <button onclick="filterActivities('all')" 
                            class="filter-btn px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                            data-filter="all">
                        All
                    </button>
                </div>
            </div>
        </div>

        <div id="activities-container" class="divide-y divide-gray-100">
        <?php
$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$sql = "SELECT * FROM user_activities ORDER BY activity_id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $firstLetter = strtoupper(substr($row['username'], 0, 1));
        $randomColor = ['bg-blue-500', 'bg-purple-500', 'bg-emerald-500', 'bg-rose-500', 'bg-amber-500'][rand(0, 4)];
        $isToday = date('Y-m-d') === date('Y-m-d', strtotime($row['activity_date']));
        echo "<div class='activity-item p-4 hover:bg-gray-50 transition-colors " . ($isToday ? 'today' : '') . "' 
                       data-date='{$row['activity_date']}'
                       data-username='" . htmlspecialchars($row['username']) . "'
                       data-activity='" . htmlspecialchars($row['activity']) . "'>
                        <div class='flex items-center gap-4'>
                            <div class='w-10 h-10 {$randomColor} rounded-full flex items-center justify-center text-white font-semibold'>{$firstLetter}</div>
                            <div class='flex-grow'>
                                <div class='flex justify-between'>
                                    <h3 class='font-medium text-gray-900'>" . htmlspecialchars($row['username']) . "</h3>
                                    <span class='text-sm text-gray-500'>" . htmlspecialchars($row['activity_date']) . "</span>
                                </div>
                                <p class='text-gray-600 mt-1'>Completed: " . htmlspecialchars($row['activity']) . "</p>
                            </div>
                            <a href='delete_activity.php?activity_id=" . urlencode($row['activity_id']) . "' 
                               class='text-gray-400 hover:text-red-500 transition-colors delete-activity'>
                                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />
                                </svg>
                            </a>
                        </div>
                    </div>";
    }
} else {
    echo '<div class="p-8 text-center text-gray-500">No Activity Details Found</div>';
}
mysqli_close($conn);
?>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Confirmation before deleting activity
    document.querySelectorAll('.delete-activity').forEach(function (element) {
        element.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default link action
            if (confirm("Are you sure you want to delete this activity?")) {
                // If the user confirms, redirect to the delete URL
                window.location.href = this.href; // Proceed with the deletion
                toastr.success('Activity deleted successfully!'); // Show success toast
            }
        });
    });
</script>

        </div>
    </div>
</section>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const activities = document.querySelectorAll('.activity-item');
    let visibleCount = 0;

    activities.forEach(activity => {
        const username = activity.dataset.username.toLowerCase();
        const activityText = activity.dataset.activity.toLowerCase();
        const isVisible = username.includes(searchTerm) || activityText.includes(searchTerm);
        
        if (isVisible && 
            (currentFilter === 'all' || 
             (currentFilter === 'today' && new Date(activity.dataset.date).toDateString() === new Date().toDateString()))) {
            activity.style.display = 'block';
            visibleCount++;
        } else {
            activity.style.display = 'none';
        }
    });

    // Update counters based on visible items
    updateCounters(visibleCount);
});

let currentFilter = 'all';

function filterActivities(filter) {
    currentFilter = filter;
    const activities = document.querySelectorAll('.activity-item');
    const buttons = document.querySelectorAll('.filter-btn');
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    let visibleCount = 0;

    buttons.forEach(btn => {
        btn.classList.toggle('bg-purple-100', btn.dataset.filter === filter);
        btn.classList.toggle('text-purple-700', btn.dataset.filter === filter);
        btn.classList.toggle('bg-gray-100', btn.dataset.filter !== filter);
        btn.classList.toggle('text-gray-600', btn.dataset.filter !== filter);
    });

    activities.forEach(activity => {
        const username = activity.dataset.username.toLowerCase();
        const activityText = activity.dataset.activity.toLowerCase();
        const matchesSearch = username.includes(searchTerm) || activityText.includes(searchTerm);
        const matchesFilter = filter === 'all' || 
                            (filter === 'today' && new Date(activity.dataset.date).toDateString() === new Date().toDateString());
        
        if (matchesSearch && matchesFilter) {
            activity.style.display = 'block';
            visibleCount++;
        } else {
            activity.style.display = 'none';
        }
    });

    updateCounters(visibleCount);
}

function updateCounters(visibleCount) {
    document.getElementById('totalActivities').textContent = visibleCount;
    document.getElementById('activeToday').textContent = Math.round(visibleCount * 0.4); // Example: 40% active today
    document.getElementById('totalUsers').textContent = new Set(
        [...document.querySelectorAll('.activity-item:not([style*="display: none"]) .font-medium.text-gray-900')]
        .map(el => el.textContent)
    ).size;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    filterActivities('all');
});
</script>

<?php
// Handle image upload
if (isset($_POST['upload_image'])) {
    $conn = mysqli_connect("localhost", "root", "", "studentform");
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $imageName = $_FILES['image']['name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($imageName);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $sql = "INSERT INTO gallery (image_name) VALUES ('$imageName')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Image uploaded successfully!');</script>";
        } else {
            echo "<script>alert('Failed to save image in database.');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }

    mysqli_close($conn);
}
?>

<section id="image-management">
<h2><b>Upload Image to Gallery</b></h2>
    <form action="admin.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="upload_image">Upload Image</button>
    </form>
</section>

<section id="gallery-management">
<h2><b>Gallery</b></h2>
    <div class="gallery">
    <?php
    $conn = mysqli_connect("localhost", "root", "", "studentform");
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM gallery ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='gallery-item'>";
            echo "<img src='uploads/" . $row['image_name'] . "' alt='Gym Image'>";
            echo "<a href='delete_image.php?id=" . $row['id'] . "' onclick='confirmDelete(" . $row['id'] . ")'>Delete</a>"; // Updated delete link
            echo "</div>";
        }
    } else {
        echo "<p>No images found in the gallery.</p>";
    }

    mysqli_close($conn);
?>

<script>
    function confirmDelete(imageId) {
        // Display a confirmation dialog
        const isConfirmed = confirm('Are you sure you want to delete this image?');
        if (isConfirmed) {
            // Redirect to delete_image.php with the image ID if confirmed
            window.location.href = 'delete_image.php?id=' + imageId;
        }
    }
</script>

    </div>
</section>

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

// Add Blog
if (isset($_POST['add_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
   

    // Check if the blog title already exists
    $checkTitleQuery = "SELECT * FROM blogs WHERE title = '$title'";
    $checkResult = mysqli_query($conn, $checkTitleQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Title already exists, show error message
        echo "<div class='message error'></div>";
    } else {
        // Handle file upload for media (image/video)
        $media = '';
        if (isset($_FILES['media']['name']) && $_FILES['media']['error'] == 0) {
            $media_name = $_FILES['media']['name'];
            $media_tmp = $_FILES['media']['tmp_name'];
            $media_path = 'uploads/' . basename($media_name);
            if (move_uploaded_file($media_tmp, $media_path)) {
                $media = $media_path; // Save the path to the uploaded file
            }
        }

        // Insert blog into database
        $query = "INSERT INTO blogs (title, description, content, media) VALUES ('$title', '$description', '$content', '$media')";
        if (mysqli_query($conn, $query)) {
            // Redirect to the same page with a fragment identifier to show blogs
           
            exit; // Ensure the script stops after the redirect
        } else {
            echo "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Fetch blogs
$blogQuery = "SELECT * FROM blogs ORDER BY id DESC";
$blogs = mysqli_query($conn, $blogQuery);

// Check for query errors
if (!$blogs) {
    die("Error fetching blogs: " . mysqli_error($conn));
}
?>

<section id="blog-management">
<!-- Header Section -->
<header> 
<h2><b>Blog Management</b></h2>
</header>

<!-- Add Blog Form -->
<section class="form-container">
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <label for="title">Blog Title</label>
        <input type="text" id="title" name="title" required placeholder="Enter Blog Title">

        <label for="description">Description</label>
        <textarea id="description" name="description" required placeholder="Enter Blog Description"></textarea>

        <label for="content">Content</label>
        <textarea id="content" name="content" required placeholder="Enter Blog Content"></textarea>

        <label for="media">Upload Media (Image/Video) (Optional)</label>
        <input type="file" id="media" name="media" accept="image/*,video/*">

        <button type="submit" name="add_blog" class="btn-submit">Add Blog</button>
    </form>
</section>

<!-- Display Blogs -->
<section id="blogs-display" class="blogs-list bg-gray-100">
    <h2><b>Your Blogs</b></h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php
        if (mysqli_num_rows($blogs) > 0) {
            while ($blog = mysqli_fetch_assoc($blogs)) {
                echo "<div class='bg-white p-4  border border-purple-300 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300'>";
                echo "<h3 class='text-lg font-semibold text-gray-800 mb-3'>" . htmlspecialchars($blog['title']) . "</h3>";

                // Check if media exists and display it
                if ($blog['media']) {
                    $mediaType = mime_content_type($blog['media']);
                    if (strpos($mediaType, 'image') !== false) {
                        echo "<div class='media mb-4'><img class='w-full h-40 object-cover rounded-lg' src='" . $blog['media'] . "' alt='Blog Media'></div>";
                    } elseif (strpos($mediaType, 'video') !== false) {
                        echo "<div class='media mb-4'><video class='w-full h-40 object-cover rounded-lg' controls><source src='" . $blog['media'] . "' type='" . $mediaType . "'>Your browser does not support the video tag.</video></div>";
                    }
                }

                // Update the delete link to trigger confirmation
                echo "<a href='#' onclick='confirmDelete(" . $blog['id'] . ")' class='inline-block text-sm bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-800 hover:text-white transition-colors duration-300'>Delete</a>";
                echo "</div>";
            }
        } else {
            echo "<p class='col-span-full text-center text-gray-500'>No blogs found to manage.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</section>

<script>
    function confirmDelete(blogId) {
        // Display a confirmation dialog
        const isConfirmed = confirm('Are you sure you want to delete this blog?');
        if (isConfirmed) {
            // Redirect to deleteblog.php with the blog ID if confirmed
            window.location.href = 'deleteblog.php?delete_id=' + blogId;
        }
    }
</script>

</body>
</html>

<style>

   /* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color:rgba(249, 250, 251, 0.66);
    color: #333;
    line-height: 1.6;
}

header {
   
    color: black;
    padding: 20px;
    text-align: center;

}


h2 {
    font-size: 1.9rem;
    margin-bottom: 15px;
}

h3 {
    font-size: 1.6rem;
    margin-bottom: 20px;
}

.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-size: 1.1rem;
}

.message.success {
    
    color: green;
}

.message.error {
   
    color: red;
}

/* Form Styling */
.form-container {
    max-width: 800px;
    margin: 30px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-container form {
    display: flex;
    flex-direction: column;
}

.form-container label {
    margin-bottom: 5px;
    font-weight: bold;
}

.form-container input,
.form-container textarea {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
    width: 100%;
}

.form-container input[type="file"] {
    padding: 8px;
}

.form-container .btn-submit {
    background-color:rgb(9, 54, 122);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form-container .btn-submit:hover {
    background-color: rgb(34, 98, 194);
}

/* Blog List Styling */
.blogs-list {
    max-width: 1000px;
    margin: 30px auto;
}

.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 40px;
}

.blog-post {
    background-color: white;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.blog-post h3 {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.blog-post p {
    font-size: 1rem;
    margin-bottom: 10px;
}

.blog-post a {
    display: inline-block;
    background-color: #dc3545;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    font-size: 1rem;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.blog-post a:hover {
    background-color: #c82333;
}

/* Media Styling */
.media img {
    width: 100%;
    border-radius: 5px;
    margin-top: 10px;
}

.media video {
    width: 100%;
    border-radius: 5px;
    margin-top: 10px;
    max-height: 500px;
}



</style>



</body>
</html>


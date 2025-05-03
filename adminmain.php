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

// Get total accounts (only Admin and Staff)
$total_query = "SELECT COUNT(*) as total FROM users WHERE account_type IN ('Admin', 'Staff')";
$total_result = mysqli_query($conn, $total_query);
$total_accounts = mysqli_fetch_assoc($total_result)['total'];

// Get admin accounts count
$admin_query = "SELECT COUNT(*) as admin_count FROM users WHERE account_type = 'Admin'";
$admin_result = mysqli_query($conn, $admin_query);
$admin_accounts = mysqli_fetch_assoc($admin_result)['admin_count'];

// Get staff accounts count
$staff_query = "SELECT COUNT(*) as staff_count FROM users WHERE account_type = 'Staff'";
$staff_result = mysqli_query($conn, $staff_query);
$staff_accounts = mysqli_fetch_assoc($staff_result)['staff_count'];

// Get new accounts (last 2 days, only Admin and Staff)
$new_query = "SELECT COUNT(*) as new_count FROM users 
              WHERE created_at >= DATE_SUB(NOW(), INTERVAL 2 DAY) 
              AND account_type IN ('Admin', 'Staff')";
$new_result = mysqli_query($conn, $new_query);
$new_accounts = mysqli_fetch_assoc($new_result)['new_count'];

// Get profile image
$profile_query = "SELECT profile_image FROM users WHERE username = ?";
$stmt = $conn->prepare($profile_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$profile_result = $stmt->get_result();
$profile_image = $profile_result->fetch_assoc()['profile_image'] ?? 'accounticon.jpg';
?>

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
        .circle-chart {
            width: 160px;
            height: 160px;
            position: relative;
        }
        .circle-chart__circle {
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
        .circle-bg {
            fill: none;
            stroke: #edf2f7;
            stroke-width: 12;
        }
        .circle-progress {
            fill: none;
            stroke-width: 12;
            stroke-linecap: round;
            animation: progress 1s ease-out forwards;
        }
        .circle-text {
            font-size: 24px;
            font-weight: bold;
            fill: #2d3748;
        }
        @keyframes progress {
            0% { stroke-dasharray: 0 100; }
        }
        .value-circle {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .value-display {
            background: white;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed h-full w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white">
        <div class="p-6">
            <!-- Profile Section -->
            <div class="flex justify-center mb-6">
                <img src="uploads/<?php echo htmlspecialchars($profile_image); ?>" 
                     alt="Profile Image of <?php echo htmlspecialchars($username); ?>" 
                     class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
            </div>
            <h2 class="text-2xl font-bold mb-8 text-center"><?php echo htmlspecialchars($username); ?></h2>

            <!-- Sidebar Links -->
            <div class="space-y-4">
                <a href="adminmain.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all" aria-label="Go to Dashboard">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="account_details.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all" aria-label="Go to Accounts">
                    <i class="fas fa-users mr-3"></i>Accounts
                </a>
               
                <a href="settings.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all" aria-label="Go to Settings">
                    <i class="fas fa-cogs mr-3"></i>Settings
                </a>
                <a href="http://localhost/fitness/Employee-Attendance-Management-System/employee-attendance-management/admin/index.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all" aria-label="Go to EMS System">
                    <i class="fas fa-users mr-3"></i>EMS System
                </a>
                <a href="loggedout.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all" aria-label="Logout">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </div>
        </div>
    </div>
</body>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Analytics Dashboard</h1>
            <p class="text-gray-600 mt-2">Account Statistics Overview</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
            <!-- Total Accounts -->
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="circle-chart mx-auto">
                    <svg viewBox="0 0 100 100">
                        <circle class="circle-bg" cx="50" cy="50" r="40"/>
                        <circle class="circle-progress" 
                                style="stroke: #22c55e" 
                                cx="50" 
                                cy="50" 
                                r="40"
                                stroke-dasharray="100 100"/>
                    </svg>
                    <div class="value-circle">
                        <div class="value-display">
                            <span class="text-2xl font-bold text-gray-800"><?php echo $total_accounts; ?></span>
                        </div>
                    </div>
                </div>
                <h3 class="text-center mt-6 text-lg font-semibold text-gray-700">Total Accounts</h3>
            </div>

            <!-- Admin Accounts -->
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="circle-chart mx-auto">
                    <svg viewBox="0 0 100 100">
                        <circle class="circle-bg" cx="50" cy="50" r="40"/>
                        <circle class="circle-progress" 
                                style="stroke: #eab308" 
                                cx="50" 
                                cy="50" 
                                r="40"
                                stroke-dasharray="<?php echo ($admin_accounts / $total_accounts) * 100; ?> 100"/>
                    </svg>
                    <div class="value-circle">
                        <div class="value-display">
                            <span class="text-2xl font-bold text-gray-800"><?php echo $admin_accounts; ?></span>
                        </div>
                    </div>
                </div>
                <h3 class="text-center mt-6 text-lg font-semibold text-gray-700">Admin Accounts</h3>
            </div>

            <!-- Staff Accounts -->
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="circle-chart mx-auto">
                    <svg viewBox="0 0 100 100">
                        <circle class="circle-bg" cx="50" cy="50" r="40"/>
                        <circle class="circle-progress" 
                                style="stroke: #3b82f6" 
                                cx="50" 
                                cy="50" 
                                r="40"
                                stroke-dasharray="<?php echo ($staff_accounts / $total_accounts) * 100; ?> 100"/>
                    </svg>
                    <div class="value-circle">
                        <div class="value-display">
                            <span class="text-2xl font-bold text-gray-800"><?php echo $staff_accounts; ?></span>
                        </div>
                    </div>
                </div>
                <h3 class="text-center mt-6 text-lg font-semibold text-gray-700">Staff Accounts</h3>
            </div>

            <!-- New Accounts -->
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="circle-chart mx-auto">
                    <svg viewBox="0 0 100 100">
                        <circle class="circle-bg" cx="50" cy="50" r="40"/>
                        <circle class="circle-progress" 
                                style="stroke: #a855f7" 
                                cx="50" 
                                cy="50" 
                                r="40"
                                stroke-dasharray="<?php echo ($new_accounts / $total_accounts) * 100; ?> 100"/>
                    </svg>
                    <div class="value-circle">
                        <div class="value-display">
                            <span class="text-2xl font-bold text-gray-800"><?php echo $new_accounts; ?></span>
                        </div>
                    </div>
                </div>
                <h3 class="text-center mt-6 text-lg font-semibold text-gray-700">New Accounts (2 Days)</h3>
            </div>
        </div>
    </div>
</body>

</html>
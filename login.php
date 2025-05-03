<?php
session_start(); // Start the session

// Check if form data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = trim($_POST['student_name']);
    $password = trim($_POST['password']);
    
    // Check for hard-coded admin credentials first
    if ($username === "Admin" && $password === "2002") {
        // Admin credentials match - set session and redirect
        $_SESSION['username'] = $username;
        $_SESSION['account_type'] = 'Admin';
        header("Location: adminmain.php");
        exit();
    }
    
    // If not hard-coded admin, proceed with database authentication
    // Database connection settings
    $conn = mysqli_connect("localhost", "root", "", "studentform");

    // Check connection
    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Sanitize username for database query
    $sanitized_username = mysqli_real_escape_string($conn, $username);

    // Prepare SQL query to check for the user in the users table
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt === false) {
        die("ERROR: Could not prepare the SQL query. " . mysqli_error($conn));
    }

    // Bind parameters and execute the query
    mysqli_stmt_bind_param($stmt, "s", $sanitized_username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if ($result) {
        $row = mysqli_fetch_assoc($result); // Fetch the row of the user
        if ($row) {
            // Verify the password (assuming passwords are hashed)
            if (password_verify($password, $row['password'])) {
                // Successful login
                $_SESSION['username'] = $sanitized_username; // Store username in session
                $_SESSION['account_type'] = $row['account_type']; // Store account type in session

                // Redirect based on account type
                if ($row['account_type'] == 'Staff') {
                    header("Location: admin.php");
                    exit();
                } else if ($row['account_type'] == 'User') {
                    header("Location: dashboard.php");
                    exit();
                } else if ($row['account_type'] == 'Admin') {
                    header("Location: adminmain.php");
                    exit();
                } else {
                    $error_message = "ERROR: Invalid account type.";
                }
            } else {
                $error_message = "ERROR: Invalid password.";
            }
        } else {
            $error_message = "ERROR: Username not found.";
        }
    } else {
        $error_message = "ERROR: Could not execute query: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close the connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <title>Login - Fitness Club</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="home-page bg-gray-900">
    

    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.php" class="active">About Us</a></li>
            <li><a href="memberships01.php">Memberships</a></li>
            <li><a href="activities_02.php">Activities</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Signin</a></li>
            <li><a href="blogs.php">Blogs</a></li>
        </ul>
    </nav>
    <br>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Increased max-width from max-w-md to max-w-2xl -->
        <div class=" w-59">
            <!-- Login Container with increased padding -->
            <div class="bg-black/10 backdrop-blur-lg p-8 rounded-2xl shadow-2xl border border-white/10">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h2 class="text-5xl font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-teal-500">
                        Welcome Back
                    </h2>
                    <p class="mt-3 text-medium text-gray-400">Please sign in to your account</p>
                </div>

                <!-- Error Message -->
                <?php if (!empty($error_message)): ?>
                    <div class="bg-red-500/50 backdrop-blur text-white px-2 py-1 rounded-lg  mb-8">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="login.php" method="POST" class="space-y-8">
                    <!-- Username Field -->
                    <div class="space-y-2">
                        <label for="student_name" class="block text-base font-small text-gray-300 flex items-center gap-2">
                            <i class="fas fa-user text-green-400 text-sm"></i>
                            Username
                        </label>
                        <input type="text" id="student_name" name="student_name" required
                            class="form-input w-full px-6 py-3 bg-white/10 border border-white/20 rounded-xl 
                            focus:ring-2 focus:ring-green-500 focus:border-transparent text-white placeholder-gray-400 text-sm"
                            placeholder="Enter your username">
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-base font-small text-gray-300 flex items-center gap-2">
                            <i class="fas fa-lock text-green-400 text-sm"></i>
                            Password
                        </label>
                        <input type="password" id="password" name="password" required
                            class="form-input w-full px-6 py-3 bg-white/10 border border-white/20 rounded-xl 
                            focus:ring-2 focus:ring-green-500 focus:border-transparent text-white placeholder-gray-400 text-sm"
                            placeholder="••••••••">
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember"
                                class="h-3 w-3 mb-1 rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                            <label for="remember" class="ml-2 text-sm block text-base text-gray-400">
                                Remember me
                            </label>
                        </div>
                        <div class="text-base">
                            <a href="#" class="text-green-400 hover:text-green-200 text-sm">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="pt-1">
                        <button type="submit" 
                            class="login-btn w-full bg-green-500 text-gray-800 px-6 py-3 rounded-xl 
                            font-semibold text-medium shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 
                            focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                            Sign In
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center ">
                        <p class="text-gray-400 text-sm">
                            Don't have an account? 
                            <a href="register.php" class="text-green-400 hover:text-green-200 text-sm">
                                Register now
                            </a>
                        </p>
                    </div>
                </form>          
</body>
<style>
  body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)), url('bg 002.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            transform: scale(1.02);
        }
        .login-btn {
            transition: all 0.3s ease;
        }
        .login-btn:hover {
            transform: scale(1.05);
        }
    </style>
</html>

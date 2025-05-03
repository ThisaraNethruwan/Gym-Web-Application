<?php
session_start();

// Database connection settings
$conn = mysqli_connect("localhost", "root", "", "studentform");

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Initialize variables to empty strings if they are not set
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$dob = isset($_SESSION['dob']) ? $_SESSION['dob'] : '';
$address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
$gender = isset($_SESSION['gender']) ? $_SESSION['gender'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$selected_plan = isset($_SESSION['selected_plan']) ? $_SESSION['selected_plan'] : 'None';
$error_message = ""; // Variable to store error message

// Check if form data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    if (isset($_POST['membership_plan'])) {
        $selected_plan = $_POST['membership_plan'];
        $_SESSION['selected_plan'] = $selected_plan;
    }

    // Check if username already exists
    $checkUserSql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkUserSql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $error_message = "ERROR: Username already exists. Please choose another one.";
    } else {
        $sql = "INSERT INTO users (username, dob, address, gender, email, password, membership_plan) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $name, $dob, $address, $gender, $email, $password, $selected_plan);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: geeting.html");
            exit();
            
        } else {
            $error_message = "ERROR: Could not register user. " . mysqli_error($conn);
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <title>Register - Fitness Club</title>
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
        .submit-btn {
            transition: all 0.3s ease;
        }
        .submit-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-900">
    <!-- Navigation -->
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
   <br>
   
     <!-- Registration Form -->
<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="w-full max-w-4xl mx-auto">
        <h1 class="text-5xl text-center font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-teal-500">
            Membership Registration
        </h1>
        <p class="text-lg text-center text-gray-400 mb-2">.....Join our fitness community today.....</p>
        
        <!-- Form Container -->
        <div class="bg-black/10 backdrop-blur-sm p-12 rounded-2xl shadow-xl border border-white/20">
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-500/50 backdrop-blur text-white px-4 py-3 rounded-lg mb-4">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-10">
                <!-- Name and DOB -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" 
                            class="form-input w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                            placeholder="Enter your name" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>" 
                            class="form-input w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white" 
                            required>
                    </div>
                </div>

                <!-- Email and Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                            class="form-input w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                            placeholder="your@email.com" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Password</label>
                        <input type="password" name="password" 
                            class="form-input w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Address and Gender -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" 
                            class="form-input w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                            placeholder="Enter your address" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Gender</label>
                        <select name="gender" 
                            class="form-input w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white" required>
                            <option value="" class="bg-gray-800">Select Gender</option>
                            <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?> class="bg-gray-800">Male</option>
                            <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?> class="bg-gray-800">Female</option>
                            <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?> class="bg-gray-800">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Membership Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-300">Membership Type</label>
                    <select name="membership_plan" 
                        class="form-input w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white" required>
                        <option value="" class="bg-gray-800">Select Membership Type</option>
                        <option value="Basic" <?php if ($selected_plan == 'Basic') echo 'selected'; ?> class="bg-gray-800">Basic</option>
                        <option value="Premium" <?php if ($selected_plan == 'Premium') echo 'selected'; ?> class="bg-gray-800">Premium</option>
                        <option value="VIP" <?php if ($selected_plan == 'VIP') echo 'selected'; ?> class="bg-gray-800">VIP</option>

                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                        class="submit-btn w-full mb-4 bg-gradient-to-r from-green-500 to-teal-500  text-white px-2 py-3 rounded-lg font-semibold text-lg shadow-md hover:shadow-green-500/30 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Register Now
                    </button>
                    <div class="text-center">
                    <p class="text-gray-400">
                        Already have an account? 
                        <a href="login.php" class="text-green-400 hover:text-green-300 font-medium ">Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
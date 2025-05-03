<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memberships | Fitness Center</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

</head>


<body>
  
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="memberships01.php" class="active">Memberships</a></li>
                <li><a href="activities_02.php">Activities</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Sign In</a></li>
                <li><a href="blogs.php">Blogs</a></li>
            </ul>
        </nav>
    </header>

    <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentform"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch membership plans
$sql = "SELECT mem_image, title, price, key_points FROM mem_plan";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plans</title>
    <style>
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 10)), url('membershipnew.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }
      

        
    </style>
<body class="bg-gray-50">
<section class="py-4 px-4 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-10">
       <br> <h2 class="text-5xl font-bold text-white mb-1">Choose Your Membership</h2>
        <div class="w-20 h-1 bg-indigo-600 mx-auto rounded-full "></div>
    </div>

    <!-- Membership Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <!-- Membership Card -->
                <div class="group rounded-xl w-auto  h-auto shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-600">
                    <!-- Image Container -->
                    <div class="h-56 overflow-hidden">
                        <img 
                            src="uploads/<?php echo htmlspecialchars($row['mem_image']); ?>" 
                            alt="<?php echo htmlspecialchars($row['title']); ?> Plan" 
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <!-- Title -->
                        <h3 class="text-lg font-bold text-white text-center mb-3">
                            <?php echo htmlspecialchars($row['title']); ?> Plan
                        </h3>

                        <!-- Price -->
                        <div class="flex justify-center items-center mb-4">
                            <span class="text-2xl font-bold text-green-600">
                                <h2>Rs.<?php echo htmlspecialchars($row['price']); ?></h2>
                            </span>
                        </div>

                        <!-- Features List -->
                        <ul class="space-y-2 mb-4">
                            <?php 
                            $keyPoints = explode(',', $row['key_points']); 
                            foreach ($keyPoints as $point): 
                            ?>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-green-500 mt-1"></i>
                                    <span class="text-white text-sm"><?php echo htmlspecialchars(trim($point)); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <!-- CTA Button -->
                        <a 
                            href="register.php" 
                            class="block w-full py-2 text-center text-white bg-blue-700 rounded-md font-medium hover:bg-blue-800 transition-colors duration-300"
                        >
                            Get Started
                        </a>
                    </div>

                    <!-- Popular Badge (optional) -->
                    <?php if (isset($row['is_popular']) && $row['is_popular']): ?>
                        <div class="absolute top-3 right-3">
                            <span class="bg-yellow-400 text-gray-900 text-xs font-semibold px-2 py-1 rounded-full">
                                Popular
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500 text-md">No membership plans available.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
</body>

</html>

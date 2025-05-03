<?php
// [Previous PHP code remains exactly the same until the HTML part]
include 'profile_header.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users SET profile_image = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $target_file, $username);
            $stmt->execute();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<script>alert('Error uploading your file.');</script>";
        }
    }
}

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body class="bg-gray-50">
  
    <!-- Main Content -->
    <main class="ml-72 p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Cover Photo Area -->
                <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                
                <!-- Profile Content -->
                <div class="relative px-8 pb-8">
                    <div class="flex flex-wrap items-start -mt-16">
                        <!-- Profile Image Section -->
                        <div class="relative">
                            <img 
                                src="<?php echo htmlspecialchars($user['profile_image'] ?: 'uploads/default-profile.png'); ?>" 
                                alt="Profile" 
                                class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover"
                            >
                            <label for="profile_image" class="absolute bottom-0 right-0 bg-blue-500 p-2 rounded-full cursor-pointer shadow-lg hover:bg-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </label>
                        </div>

                        <!-- User Info Next to Profile -->
                      <div class="ml-6 mt-12">
                      <br> <h1 class="text-3xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($user['username']); ?></h1>
                            <p class="text-blue-600 font-medium mb-2"><?php echo htmlspecialchars($user['membership_plan']); ?></p>
                            <div class="inline-block bg-blue-50 px-4 py-2 rounded-lg">
                                <p class="text-sm text-blue-600">Member since: <?php echo htmlspecialchars($user['created_at']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- User Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Gender</p>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($user['gender']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($user['email']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Address</p>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($user['address']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Date of Birth</p>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($user['dob']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden File Input -->
            <form method="POST" enctype="multipart/form-data" class="hidden">
                <input 
                    type="file" 
                    name="profile_image" 
                    id="profile_image" 
                    class="hidden"
                    accept=".jpg,.jpeg,.png,.gif"
                    onchange="this.form.submit()"
                >
            </form>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>
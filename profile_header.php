<?php
// [Previous PHP code remains exactly the same until the HTML part]
session_start();

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
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-72 bg-gradient-to-b from-blue-800 to-blue-900 text-white shadow-xl">
        <div class="flex flex-col h-full">
            <div class="px-6 py-8">
                <h2 class="text-2xl font-bold text-center mb-2"><?php echo htmlspecialchars($username); ?></h2>
                <div class="w-20 h-1 bg-blue-500 mx-auto rounded-full"></div>
            </div>
            
            <nav class="flex-1 px-4">
                <a href="dashboard.php" class="block px-4 py-3 mb-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Dashboard
                </a>
                <a href="activities.php" class="block px-4 py-3 mb-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Activities
                </a>
                <a href="portfolio.php" class="block px-4 py-3 mb-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Your Portfolio
                </a>
                <a href="user_details_cards.php" class="block px-4 py-3 mb-2 hover:bg-blue-700 rounded-lg">
                    Your Account
                </a>
                <a href="loggedout.php" class="block px-4 py-3 mb-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Logout
                </a>
            </nav>
        </div>
    </aside>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>
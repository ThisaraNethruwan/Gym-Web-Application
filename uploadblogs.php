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

// Handle the form submission to upload a new blog
if (isset($_POST['upload_blog'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author = mysqli_real_escape_string($conn, $username);

    // Handle file upload for media (image/video)
    $media = '';
    if (isset($_FILES['media']['name']) && $_FILES['media']['error'] == 0) {
        $media_name = $_FILES['media']['name'];
        $media_tmp = $_FILES['media']['tmp_name'];
        $media_path = 'uploads/' . basename($media_name);

        // Move the uploaded file to the server's designated folder
        if (move_uploaded_file($media_tmp, $media_path)) {
            $media = $media_path; // Save the file path to the database
        }
    }

    // Insert blog into the database
    $query = "INSERT INTO blogs (title, description, content, author, media) 
              VALUES ('$title', '$description', '$content', '$author', '$media')";

    if (mysqli_query($conn, $query)) {
        echo "<div class='message success'>Blog uploaded successfully!</div>";
    } else {
        echo "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Blog</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<!-- Header with greeting -->
<header>
   
</header>

<!-- Main Content -->
<main>
    <section class="upload-form">
        <h2>Upload New Blog</h2>

        <!-- Blog Upload Form -->
        <form action="uploadblogs.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required></textarea>
            </div>

            <div class="form-group">
                <label for="media">Upload Image/Video (Optional)</label>
                <input type="file" id="media" name="media" accept="image/*,video/*">
            </div>

            <button type="submit" name="upload_blog" class="submit-btn">Upload Blog</button>
        </form>
    </section>
</main>
<style>
/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background: #f4f6f9;
    color: #333;
    line-height: 1.6;
}

header {
    background-color: #58ff33;
    color: white;
    padding: 20px;
    text-align: center;
}

h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

h2 {
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.upload-form {
    max-width: 600px;
    margin: 30px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.upload-form h2 {
    color: #333;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-size: 1rem;
    margin-bottom: 5px;
    display: block;
}

input[type="text"], textarea {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-top: 5px;
}

textarea {
    height: 150px;
    resize: vertical;
}

input[type="file"] {
    display: block;
    margin-top: 5px;
}

.submit-btn {
    width: 100%;
    padding: 12px;
    background-color: #58ff33;
    color: white;
    font-size: 1.2rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #45d73b;
}

/* Success and Error Message */
.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.message.success {
    background-color: #28a745;
    color: white;
}

.message.error {
    background-color: #dc3545;
    color: white;
}




</style>
</body>
</html>

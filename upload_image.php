<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "studentform");

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the image upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    // Save the image to the server's "uploads" folder
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Insert the image details into the database
        $description = htmlspecialchars(trim($_POST['description']));
        $sql = "INSERT INTO gallery (image_url, description) VALUES ('$target', '$description')";

        if (mysqli_query($conn, $sql)) {
            echo "Image uploaded and saved successfully!";
        } else {
            echo "ERROR: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
</head>
<body>
    <h2>Upload Gym Image</h2>
    <form action="upload_image.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required><br><br>
        <input type="text" name="description" placeholder="Image Description" required><br><br>
        <button type="submit">Upload Image</button>
    </form>
</body>
</html>

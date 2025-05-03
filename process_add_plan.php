<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $keyPoints = isset($_POST['key_points']) ? $_POST['key_points'] : [];
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Convert key points array to a string for storage
    $keyPointsString = implode(',', $keyPoints);

    // File upload handling
    $target_dir = "uploads/";
    $mem_image = basename($_FILES["mem_image"]["name"]);
    $target_file = $target_dir . $mem_image;

    if (!empty($mem_image) && move_uploaded_file($_FILES["mem_image"]["tmp_name"], $target_file)) {
        // Insert into the database
        $sql = "INSERT INTO mem_plan (title, key_points, price, mem_image) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $title, $keyPointsString, $price, $mem_image);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: upload_membership.php?success=Plan added successfully");
            exit;
        } else {
            header("Location: upload_membership.php?error=Error adding plan: " . mysqli_error($conn));
            exit;
        }
    } else {
        header("Location: upload_membership.php?error=Error uploading image.");
        exit;
    }
} else {
    header("Location: upload_membership.php");
    exit;
}
?>

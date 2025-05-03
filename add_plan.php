<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $key_points = mysqli_real_escape_string($conn, $_POST['key_points']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $file_name = uniqid() . "_" . $_FILES["mem_image"]["name"];
    $target_file = "uploads/" . $file_name;

    if (move_uploaded_file($_FILES["mem_image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO mem_plan (title, key_points, price, mem_image) 
                VALUES ('$title', '$key_points', '$price', '$file_name')";
        if (mysqli_query($conn, $sql)) {
            header("Location: admin.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

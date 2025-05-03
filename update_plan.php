<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $key_points = mysqli_real_escape_string($conn, $_POST['key_points']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    if ($_FILES["mem_image"]["size"] > 0) {
        $file_name = uniqid() . "_" . $_FILES["mem_image"]["name"];
        $target_file = "uploads/" . $file_name;

        if (move_uploaded_file($_FILES["mem_image"]["tmp_name"], $target_file)) {
            $sql = "UPDATE mem_plan SET title='$title', key_points='$key_points', price='$price', mem_image='$file_name' WHERE id=$id";
        }
    } else {
        $sql = "UPDATE mem_plan SET title='$title', key_points='$key_points', price='$price' WHERE id=$id";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

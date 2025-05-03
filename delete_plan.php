<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $result = mysqli_query($conn, "SELECT mem_image FROM mem_plan WHERE id=$id");
    $row = mysqli_fetch_assoc($result);

    if ($row['mem_image']) {
        unlink("uploads/" . $row['mem_image']);
    }

    $sql = "DELETE FROM mem_plan WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: upload_membership.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php
include './config.php';
session_start();

if (!isset($_SESSION['usermail'])) {
    header("location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "DELETE FROM resev_ruangan WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['delete_status'] = "success";
    } else {
        $_SESSION['delete_status'] = "error";
    }
}

header("Location: home.php");
exit();
?>

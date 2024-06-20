<?php
session_start();
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteSql = "DELETE FROM resev_ruangan WHERE id = $id";

    if (mysqli_query($conn, $deleteSql)) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Reservation deleted successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error deleting reservation: ' . mysqli_error($conn);
    }

    header("Location: roombook.php");
    exit();
} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid reservation ID';
    header("Location: roombook.php");
    exit();
}

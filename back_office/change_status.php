<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['user_id']) && isset($_GET['status'])) {
    $user_id = $_GET['user_id'];
    $status = $_GET['status'];

    $user_id = mysqli_real_escape_string($conn, $user_id);
    $status = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE users SET status = '$status' WHERE user_id = $user_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_accounts.php");
        exit();
    } else {
        echo "Error updating user status: " . mysqli_error($conn);
    }
} else {
    header("Location: manage_accounts.php");
    exit();
}
?>

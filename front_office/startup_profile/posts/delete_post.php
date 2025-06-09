<?php
session_start();
include('../../includes/db.php');

if (!isset($_SESSION['email']) || !isset($_GET['id'])) {
    header("Location: ../../authentication/login.php");
    exit();
}

$post_id = intval($_GET['id']);

mysqli_query($conn, "DELETE FROM media WHERE post_id = '$post_id'");

mysqli_query($conn, "DELETE FROM posts WHERE post_id = '$post_id'");

header("Location: ../startup_profile.php");
exit();
?>

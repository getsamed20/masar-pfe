<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['email']) || !isset($_GET['id'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$post_id = intval($_GET['id']);

$email = $_SESSION['email'];
$user_check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($user_check);

$institution_check = mysqli_query($conn, "SELECT * FROM public_institutions WHERE user_id = " . $user['user_id']);
$institution = mysqli_fetch_assoc($institution_check);
$institution_id = $institution['institution_id'];

$post_check = mysqli_query($conn, "SELECT * FROM posts_institution WHERE post_id = $post_id AND institution_id = $institution_id");
if (mysqli_num_rows($post_check) === 0) {
    header("Location: public_institution_profile.php?error=unauthorized");
    exit();
}

mysqli_query($conn, "DELETE FROM media WHERE post_id = $post_id");

mysqli_query($conn, "DELETE FROM posts_institution WHERE post_id = $post_id");

header("Location: ../public_institution_profile.php");
exit();
?>

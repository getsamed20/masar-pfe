<?php
session_start();
include('../../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);

    // Get media files associated with the post
    $media_query = mysqli_query($conn, "SELECT file_path FROM media WHERE post_institution_id = $post_id");
    while ($media = mysqli_fetch_assoc($media_query)) {
        $file_path = '../../uploads/' . $media['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path); // delete the file from the server
        }
    }

    // Delete media entries
    mysqli_query($conn, "DELETE FROM media WHERE post_institution_id = $post_id");

    // Delete the post itself
    mysqli_query($conn, "DELETE FROM posts_institution WHERE post_id = $post_id");

    header('Location: ../public_institution_profile.php');
    exit();
} 
?>

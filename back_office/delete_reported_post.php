<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $source = mysqli_real_escape_string($conn, $_POST['source']);

    // Delete from the appropriate post table
    if ($source === 'startup') {
        // Delete associated media
        $media_query = "SELECT file_path FROM media WHERE post_id = '$post_id'";
        $media_result = mysqli_query($conn, $media_query);
        while ($media = mysqli_fetch_assoc($media_result)) {
            $file = '../front_office/uploads/' . $media['file_path'];
            if (file_exists($file)) {
                unlink($file);
            }
        }
        mysqli_query($conn, "DELETE FROM media WHERE post_id = '$post_id'");
        mysqli_query($conn, "DELETE FROM posts WHERE post_id = '$post_id'");
    } elseif ($source === 'institution') {
        // Delete associated media
        $media_query = "SELECT file_path FROM media WHERE post_institution_id = '$post_id'";
        $media_result = mysqli_query($conn, $media_query);
        while ($media = mysqli_fetch_assoc($media_result)) {
            $file = '../front_office/uploads/' . $media['file_path'];
            if (file_exists($file)) {
                unlink($file);
            }
        }
        mysqli_query($conn, "DELETE FROM media WHERE post_institution_id = '$post_id'");
        mysqli_query($conn, "DELETE FROM posts_institution WHERE post_id = '$post_id'");
    }

    // Delete all related reports
    mysqli_query($conn, "DELETE FROM reports WHERE post_id = '$post_id' OR post_institution_id = '$post_id'");

    header("Location: admin_reported_posts.php?success=deleted");
    exit();
} else {
    header("Location: admin_reported_posts.php");
    exit();
}
?>

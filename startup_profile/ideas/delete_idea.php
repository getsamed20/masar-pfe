<?php
include '../../include/db.php';
session_start();

if (!isset($_SESSION['startup_id'])) {
    header("Location: ../login.php");
    exit();
}

$startup_id = $_SESSION['startup_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idea_id = mysqli_real_escape_string($conn, $_POST['idea_id']);

    // Check if the idea belongs to the current startup
    $check = mysqli_query($conn, "SELECT * FROM ideas WHERE idea_id = '$idea_id' AND startup_id = '$startup_id'");
    if (mysqli_num_rows($check) == 0) {
        $_SESSION['error'] = "Unauthorized or idea not found.";
        header("Location: ../startup_profile.php");
        exit();
    }

    // Delete associated media files (if needed)
    $media_query = mysqli_query($conn, "SELECT file_path FROM media WHERE idea_id = '$idea_id'");
    while ($media = mysqli_fetch_assoc($media_query)) {
        $file_path = '../../' . $media['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Delete media records
    mysqli_query($conn, "DELETE FROM media WHERE idea_id = '$idea_id'");

    // Delete the idea
    $delete = mysqli_query($conn, "DELETE FROM ideas WHERE idea_id = '$idea_id'");

    if ($delete) {
        $_SESSION['success'] = "Idea deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete idea. Please try again.";
    }

    header("Location: ../startup_profile.php");
    exit();
} else {
    header("Location: ../startup_profile.php");
    exit();
}

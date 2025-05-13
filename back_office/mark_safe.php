<?php
session_start();
include('db.php');

// Only admins allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Check if POST data is set
if (isset($_POST['post_id'], $_POST['source'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $source = $_POST['source'];

    // Determine which column to use in the reports table
    if ($source === 'startup') {
        $delete_query = "DELETE FROM reports WHERE post_id = '$post_id'";
    } elseif ($source === 'institution') {
        $delete_query = "DELETE FROM reports WHERE post_institution_id = '$post_id'";
    } else {
        // Invalid source
        header("Location: admin_reported_posts.php?error=invalid_source");
        exit();
    }

    // Execute the deletion
    if (mysqli_query($conn, $delete_query)) {
        header("Location: admin_reported_posts.php?success=safe");
        exit();
    } else {
        header("Location: admin_reported_posts.php?error=query_failed");
        exit();
    }
} else {
    header("Location: admin_reported_posts.php?error=missing_data");
    exit();
}
?>

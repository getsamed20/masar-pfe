<?php
include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);

    $update = mysqli_query($conn, "UPDATE posts SET reported = TRUE WHERE post_id = '$post_id'");

    if ($update) {
        header("Location: " . $_SERVER['HTTP_REFERER']); 
        exit;
    } else {
        echo "Failed to report the post.";
    }
} else {
    echo "Invalid request.";
}
?>

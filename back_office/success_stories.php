<?php
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
$stories = mysqli_query($conn, "SELECT * FROM success_stories ORDER BY created_at DESC");

while ($story = mysqli_fetch_assoc($stories)) {
    echo '<div class="card mb-4">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . htmlspecialchars($story['title']) . '</h5>';
    echo '<p>' . nl2br(htmlspecialchars($story['content'])) . '</p>';

    $story_id = $story['story_id'];
    $mediaResult = mysqli_query($conn, "SELECT * FROM media WHERE story_id = $story_id");

    while ($item = mysqli_fetch_assoc($mediaResult)) {
        if ($item['media_type'] === 'image') {
            echo '<img src="' . htmlspecialchars($item['file_path']) . '" class="img-fluid rounded mb-2" style="max-width: 100%;">';
        } elseif ($item['media_type'] === 'video') {
            echo '<video controls class="w-100 mb-2"><source src="' . htmlspecialchars($item['file_path']) . '"></video>';
        }
    }

    echo '<small class="text-muted">Published on ' . $story['created_at'] . '</small>';
    echo '</div></div>';
}
?>

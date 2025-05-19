<?php 
include('../includes/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid story ID.";
    exit;
}

$story_id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM success_stories WHERE story_id = $story_id");

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Story not found.";
    exit;
}

$story = mysqli_fetch_assoc($result);

$title = htmlspecialchars($story['title']);
$content = nl2br(htmlspecialchars($story['content']));
$published_at = $story['created_at'];

echo "<div class='container py-4'>";
echo "<h2>$title</h2>";
echo "<p>$content</p>";

$mediaResult = mysqli_query($conn, "SELECT * FROM media WHERE story_id = $story_id");

while ($item = mysqli_fetch_assoc($mediaResult)) {
    $media_path = '../../back_office/' . $item['file_path'];

    if ($item['media_type'] === 'image') {
        echo '<img src="' . htmlspecialchars($media_path) . '" class="img-fluid rounded mb-3" style="max-width: 100%;">';
    } elseif ($item['media_type'] === 'video') {
        echo '<video controls class="w-100 mb-3"><source src="' . htmlspecialchars($media_path) . '"></video>';
    }
}

echo "<small class='text-muted'>Published on $published_at</small>";
echo "</div>";
?>

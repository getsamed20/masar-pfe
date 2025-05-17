<?php  
include('../includes/db.php');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    $stories = mysqli_query($conn, "SELECT * FROM success_stories ORDER BY created_at DESC");

    while ($story = mysqli_fetch_assoc($stories)) {
        $story_id = $story['story_id'];
        $title = htmlspecialchars($story['title']);
        $content = htmlspecialchars($story['content']);
        // Limit content to 150 chars for preview
        $preview = strlen($content) > 150 ? substr($content, 0, 150) . '…' : $content;

        echo '<div class="col">';
        echo '<div class="card h-100 rounded-4 shadow-sm">';  // rounded corners, shadow, full height
        echo '<div class="card-body d-flex flex-column">';
        // Title as clickable link
        echo "<h5 class='card-title'><a href='story_detail.php?id=$story_id' class='text-decoration-none stretched-link'>$title</a></h5>";
        echo "<p class='card-text flex-grow-1'>" . nl2br($preview) . "</p>";
        echo "<a href='story_detail.php?id=$story_id' class='btn btn-primary mt-auto'>Read More</a>";
        echo '<small class="text-muted mt-3">Published on ' . $story['created_at'] . '</small>';
        echo '</div></div></div>';
    }
    ?>
  </div>
</div>

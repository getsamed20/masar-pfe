<?php 
$posts = mysqli_query($conn, "SELECT * FROM posts_institution WHERE institution_id = '$institution_id' ORDER BY created_at DESC");

echo '<div class="mb-5"><h4>My Posts</h4>';

while ($p = mysqli_fetch_assoc($posts)) {
    $post_id = $p['post_id'];
    $media = mysqli_query($conn, "SELECT * FROM media WHERE post_institution_id = '$post_id'");
    
    $images = [];
    $videos = [];

    while ($m = mysqli_fetch_assoc($media)) {
        if ($m['media_type'] === 'image') {
            $images[] = $m['file_path'];
        } else {
            $videos[] = $m['file_path'];
        }
    }

    echo '<div class="card mb-4"><div class="card-body">';
    echo '<p>' . nl2br(htmlspecialchars($p['content'])) . '</p>';

    // Image Carousel
    if (!empty($images)) {
        $carouselId = "carouselPost" . $post_id;
        echo '<div id="' . $carouselId . '" class="carousel slide mb-3" data-bs-ride="carousel">';
        echo '<div class="carousel-inner">';
        foreach ($images as $index => $img) {
            $activeClass = $index === 0 ? 'active' : '';
            echo '<div class="carousel-item ' . $activeClass . '">';
            echo '<img src="../uploads/' . $img . '" class="d-block w-100 rounded">';
            echo '</div>';
        }
        echo '</div>';
        if (count($images) > 1) {
            echo '<button class="carousel-control-prev" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>';
            echo '<button class="carousel-control-next" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>';
        }
        echo '</div>';
    }

    // Video Preview
    foreach ($videos as $vid) {
        echo '<video controls class="w-100 rounded mb-2">
                <source src="../uploads/' . $vid . '" type="video/mp4">
                Your browser does not support the video tag.
              </video>';
    }

    // Buttons
    echo '<div class="mt-3 d-flex justify-content-end gap-2">';
    echo '<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPostModal' . $post_id . '">Edit</button>';
    echo '<a href="posts_institution/delete_post_institution.php?id=post_' . $post_id . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete this post?\')">Delete</a>';
    echo '</div>';

    echo '</div></div>';

    // Edit Modal
    echo '
    <div class="modal fade" id="editPostModal' . $post_id . '" tabindex="-1" aria-labelledby="editPostModalLabel' . $post_id . '" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="posts_institution/edit_post_institution.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPostModalLabel' . $post_id . '">Edit Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="post_id" value="' . $post_id . '">
                        <textarea name="content" class="form-control" rows="4" required>' . htmlspecialchars($p['content']) . '</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>';
}

echo '</div>';
?>

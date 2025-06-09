<?php 

$posts = mysqli_query($conn, "SELECT * FROM posts WHERE startup_id = '$startup_id' ORDER BY created_at DESC");


while ($p = mysqli_fetch_assoc($posts)) {
    $post_id = $p['post_id'];
    $media = mysqli_query($conn, "SELECT * FROM media WHERE post_id = '$post_id'");
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

    foreach ($videos as $vid) {
        echo '<video controls class="w-100 rounded mb-2">
                <source src="../uploads/' . $vid . '" type="video/mp4">
                Your browser does not support the video tag.
              </video>';
    }

    echo '<div class="mt-3 d-flex justify-content-end gap-2">';
    echo '<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPostModal<?= $post_id ?>">Edit</button>';
    echo '<a href="posts/delete_post.php?id=post_' . $post_id . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete this post?\')">Delete</a>';
    echo '</div>';

    echo '</div></div>';
}

?>


<div class="modal fade" id="editPostModal<?= $post_id ?>" tabindex="-1" aria-labelledby="editPostModalLabel<?= $post_id ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="posts/edit_post.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="editPostModalLabel<?= $post_id ?>">Edit Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="post_id" value="<?= $post_id ?>">
          <textarea name="content" class="form-control" rows="4"><?= htmlspecialchars($p['content']) ?></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<style>
.card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            height: 380px;
            padding: 20px;
            width: 100%;
            margin: 0 auto;
            border:
        }

</style>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

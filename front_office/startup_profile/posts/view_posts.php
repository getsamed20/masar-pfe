<?php
if (!isset($startup_id)) {
    echo "<div class='alert alert-danger'>Startup ID is missing.</div>";
    exit;
}

include('../includes/db.php');

$reporter_name = '';
if (isset($_SESSION['startup_id'])) {
    $startup_id_session = intval($_SESSION['startup_id']);
    $res = mysqli_query($conn, "SELECT startup_name FROM startups WHERE startup_id = '$startup_id_session'");
    if ($row = mysqli_fetch_assoc($res)) {
        $reporter_name = $row['startup_name'];
    }
}

$reporter_email = $_SESSION['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_post_id'], $_POST['report_reason'])) {
    $report_post_id = intval($_POST['report_post_id']);
    $reason = mysqli_real_escape_string($conn, $_POST['report_reason']);
    $name_safe = mysqli_real_escape_string($conn, $reporter_name);
    $email_safe = mysqli_real_escape_string($conn, $reporter_email);

    $query = "INSERT INTO reports (post_id, reporter_name, reporter_email, reason) 
              VALUES ('$report_post_id', '$name_safe', '$email_safe', '$reason')";
    mysqli_query($conn, $query);
}

$posts = mysqli_query($conn, "SELECT * FROM posts WHERE startup_id = '$startup_id' ORDER BY created_at DESC");

if (mysqli_num_rows($posts) === 0) {
    echo "<p>No posts found.</p>";
}

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
                    <span class="carousel-control-prev-icon"></span>
                  </button>';
            echo '<button class="carousel-control-next" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                  </button>';
        }
        echo '</div>';
    }

    foreach ($videos as $vid) {
        echo '<video controls class="w-100 mb-2"><source src="../uploads/' . $vid . '" type="video/mp4">Your browser does not support the video tag.</video>';
    }

    echo '
    <button class="btn btn-outline-danger btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#reportModal" onclick="setReportPostId(' . $post_id . ')">
        Report
    </button>';

    echo '</div></div>';
}
?>

<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" onsubmit="return confirm('Are you sure you want to report this post?');">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportModalLabel">Report Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="report_post_id" name="report_post_id">
          <input type="hidden" name="reporter_name" value="<?= htmlspecialchars($reporter_name) ?>">
          <input type="hidden" name="reporter_email" value="<?= htmlspecialchars($reporter_email) ?>">

          <label for="report_reason" class="form-label">Reason</label>
          <select id="report_reason" name="report_reason" class="form-select" required>
            <option value="">-- Select Report Reason --</option>
            <option value="Spam">Spam</option>
            <option value="Inappropriate">Inappropriate Content</option>
            <option value="Misinformation">Misinformation</option>
            <option value="Hate">Hate Speech / Harassment</option>
            <option value="Plagiarism">Plagiarism</option>
            <option value="Off-topic">Off-topic</option>
            <option value="Fake Identity">Fake Identity</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning btn-sm">Submit Report</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function setReportPostId(postId) {
    document.getElementById('report_post_id').value = postId;
}
</script>

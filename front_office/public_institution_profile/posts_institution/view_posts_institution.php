<?php
if (!isset($institution_id)) {
    echo "<div class='alert alert-danger'>Institution ID is missing.</div>";
    exit;
}

include('../includes/db.php');

$reporter_name = '';
$reporter_email = $_SESSION['email'] ?? '';

if (isset($_SESSION['institution_id'])) {
    $institution_id_session = intval($_SESSION['institution_id']);
    $res = mysqli_query($conn, "SELECT institution_name FROM public_institutions WHERE institution_id = '$institution_id_session'");
    if ($row = mysqli_fetch_assoc($res)) {
        $reporter_name = $row['institution_name'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_post_institution_id'], $_POST['report_reason'])) {
    $post_institution_id = intval($_POST['report_post_institution_id']);
    $reason = mysqli_real_escape_string($conn, $_POST['report_reason']);
    $name_safe = mysqli_real_escape_string($conn, $reporter_name);
    $email_safe = mysqli_real_escape_string($conn, $reporter_email);

    $query = "INSERT INTO reports (post_institution_id, reporter_name, reporter_email, reason) 
              VALUES ('$post_institution_id', '$name_safe', '$email_safe', '$reason')";
    mysqli_query($conn, $query);
}

$posts = mysqli_query($conn, "SELECT * FROM posts_institution WHERE institution_id = '$institution_id' ORDER BY created_at DESC");

echo '<div class="mb-5"><h4>Institution Posts</h4>';

while ($p = mysqli_fetch_assoc($posts)) {
    $post_institution_id = $p['post_id'];
    $media = mysqli_query($conn, "SELECT * FROM media WHERE post_institution_id = '$post_institution_id'");

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
        $carouselId = "carouselPostInst" . $post_institution_id;
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

    // Videos
    foreach ($videos as $vid) {
        echo '<video controls class="w-100 rounded mb-2">
                <source src="../uploads/' . $vid . '" type="video/mp4">
                Your browser does not support the video tag.
              </video>';
    }

    // Report Button
    echo '
    <button class="btn btn-outline-danger btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#reportModalInstitution" onclick="setInstitutionPostId(' . $post_institution_id . ')">
        Report
    </button>';

    echo '</div></div>';
}

echo '</div>';
?>

<!-- Report Modal (shared) -->
<div class="modal fade" id="reportModalInstitution" tabindex="-1" aria-labelledby="reportModalLabelInst" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" onsubmit="return confirm('Are you sure you want to report this institution post?');">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportModalLabelInst">Report Institution Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="report_post_institution_id" name="report_post_institution_id">
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
function setInstitutionPostId(postId) {
    document.getElementById('report_post_institution_id').value = postId;
}
</script>

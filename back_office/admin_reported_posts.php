<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Handle reason filter
$reason_filter = isset($_GET['reason']) ? mysqli_real_escape_string($conn, $_GET['reason']) : '';

// Build WHERE clause
$where = "WHERE 1";
if ($reason_filter !== '') {
    $where .= " AND r.reason = '$reason_filter'";
}

// Query both startup and institution posts
$query = "
    SELECT 
        r.report_id,
        r.post_id,
        r.reporter_name,
        r.reporter_email,
        r.reason,
        r.reported_at,
        p.content,
        'startup' AS source
    FROM reports r
    JOIN posts p ON r.post_id = p.post_id
    $where

    UNION

    SELECT 
        r.report_id,
        r.post_institution_id,
        r.reporter_name,
        r.reporter_email,
        r.reason,
        r.reported_at,
        pi.content,
        'institution' AS source
    FROM reports r
    JOIN posts_institution pi ON r.post_institution_id = pi.post_id
    $where

    ORDER BY reported_at DESC
";

$reports = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Reported Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
    <h2 class="mb-4">Reported Posts</h2>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-4">
            <select name="reason" class="form-select">
                <option value="">All Reasons</option>
                <option value="Spam" <?= $reason_filter === 'Spam' ? 'selected' : '' ?>>Spam</option>
                <option value="Harassment" <?= $reason_filter === 'Harassment' ? 'selected' : '' ?>>Harassment</option>
                <option value="Inappropriate" <?= $reason_filter === 'Inappropriate' ? 'selected' : '' ?>>Inappropriate Content</option>
                <option value="Offensive Content" <?= $reason_filter === 'Offensive Content' ? 'selected' : '' ?>>Offensive Content</option>
                <option value="Hate" <?= $reason_filter === 'Hate' ? 'selected' : '' ?>>Hate</option>
                <option value="Plagiarism" <?= $reason_filter === 'Plagiarism' ? 'selected' : '' ?>>Plagiarism</option>
                <option value="Off-topic" <?= $reason_filter === 'Off-topic' ? 'selected' : '' ?>>Off-topic</option>
                <option value="Fake Identity" <?= $reason_filter === 'Fake Identity' ? 'selected' : '' ?>>Fake Identity</option>
                <option value="Other" <?= $reason_filter === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
        </div>
    </form>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'safe'): ?>
        <div class="alert alert-success">Post marked as safe and removed from reports.</div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($reports) === 0): ?>
        <div class="alert alert-info">No reported posts found.</div>
    <?php else: ?>
        <?php while ($r = mysqli_fetch_assoc($reports)): ?>
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    Report Reason: <?= htmlspecialchars($r['reason']) ?> (<?= ucfirst($r['source']) ?> post)
                </div>
                <div class="card-body">
                    <p><strong>Post ID:</strong> <?= $r['post_id'] ?></p>
                    <p><strong>Reported By:</strong> <?= htmlspecialchars($r['reporter_name']) ?> (<?= htmlspecialchars($r['reporter_email']) ?>)</p>
                    <p><strong>Reported At:</strong> <?= $r['reported_at'] ?></p>



                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewPostModal<?= $r['report_id'] ?>">View Post</button>

                        <form method="POST" action="mark_safe.php" onsubmit="return confirm('Mark this post as safe?');">
                            <input type="hidden" name="post_id" value="<?= $r['post_id'] ?>">
                            <input type="hidden" name="source" value="<?= $r['source'] ?>">
                            <button type="submit" class="btn btn-success btn-sm">Safe</button>
                        </form>

<form method="POST" action="delete_reported_post.php" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            <input type="hidden" name="post_id" value="<?= $r['post_id'] ?>">
                            <input type="hidden" name="source" value="<?= $r['source'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View Modal -->
            <div class="modal fade" id="viewPostModal<?= $r['report_id'] ?>" tabindex="-1" aria-labelledby="viewPostModalLabel<?= $r['report_id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewPostModalLabel<?= $r['report_id'] ?>">Post Content</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><?= nl2br(htmlspecialchars($r['content'])) ?></p>
                                                <!-- Media -->
                    <?php
                    $post_id = $r['post_id'];
                    $source = $r['source'];
                    $media_query = ($source === 'startup') ?
                        "SELECT * FROM media WHERE post_id = '$post_id'" :
                        "SELECT * FROM media WHERE post_institution_id = '$post_id'";
                    $media = mysqli_query($conn, $media_query);

                    while ($m = mysqli_fetch_assoc($media)):
                        if ($m['media_type'] === 'image'): ?>
                            <img src="../front_office/uploads/<?= htmlspecialchars($m['file_path']) ?>" class="img-fluid rounded mb-2" style="max-height: 300px;">
                        <?php elseif ($m['media_type'] === 'video'): ?>
                            <video controls class="w-100 mb-2">
                                <source src="../front_office/uploads/<?= htmlspecialchars($m['file_path']) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif;
                    endwhile;
                    ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

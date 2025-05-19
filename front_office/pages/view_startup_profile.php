<?php  
session_start();
include('../includes/db.php');
include('../components/navbar.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid profile link.</div></div>";
    exit;
}

$query = "SELECT * FROM startups WHERE user_id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Profile not found.</div></div>";
    exit;
}

$startup_id = $data['startup_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Masar Platform - Startup Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-info { background-color: #f8f9fa; padding: 20px; border-radius: 8px; }
        .media-preview img, .media-preview video { max-width: 100%; max-height: 300px; margin: 5px 0; }
        .badge { font-size: 0.8rem; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-info mb-4">
                <?php if (!empty($data['logo'])): ?>
                    <div class="text-center mb-3">
                        <img src="../uploads/<?php echo htmlspecialchars($data['logo']); ?>" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                <?php endif; ?>
                <h4>Contact Info</h4>
                <?php if (!empty($data['contact_email'])): ?><p><strong>Email:</strong> <?php echo htmlspecialchars($data['contact_email']); ?></p><?php endif; ?>
                <?php if (!empty($data['phone_number'])): ?><p><strong>Phone:</strong> <?php echo htmlspecialchars($data['phone_number']); ?></p><?php endif; ?>
                <?php if (!empty($data['address'])): ?><p><strong>Address:</strong> <?php echo htmlspecialchars($data['address']); ?></p><?php endif; ?>
                <?php if (!empty($data['website_url'])): ?><p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($data['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($data['website_url']); ?></a></p><?php endif; ?>
                <h4 class="mt-3">Social</h4>
                <ul>
                    <?php if (!empty($data['facebook_link'])): ?><li><a href="<?php echo htmlspecialchars($data['facebook_link']); ?>" target="_blank">Facebook</a></li><?php endif; ?>
                    <?php if (!empty($data['linkedin_link'])): ?><li><a href="<?php echo htmlspecialchars($data['linkedin_link']); ?>" target="_blank">LinkedIn</a></li><?php endif; ?>
                    <?php if (!empty($data['x_link'])): ?><li><a href="<?php echo htmlspecialchars($data['x_link']); ?>" target="_blank">X</a></li><?php endif; ?>
                    <?php if (!empty($data['instagram_link'])): ?><li><a href="<?php echo htmlspecialchars($data['instagram_link']); ?>" target="_blank">Instagram</a></li><?php endif; ?>
                </ul>
                <?php if (isset($_SESSION['email'])): ?>
                    <a href="../chat/chat.php?id=<?php echo $id; ?>&type=startup" class="btn btn-success">Start Chat</a>
                <?php else: ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Start Chat</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8">
            <div class="mt-4">
                <ul class="nav nav-tabs" id="createTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="post-tab" data-bs-toggle="tab" href="#post" role="tab" aria-controls="post" aria-selected="true">Posts</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="idea-tab" data-bs-toggle="tab" href="#idea" role="tab" aria-controls="idea" aria-selected="false">Ideas</a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="createTabsContent">
                    <div class="tab-pane fade show active" id="post" role="tabpanel" aria-labelledby="post-tab">
                        <?php include('../startup_profile/posts/view_posts.php'); ?>
                    </div>
                    <div class="tab-pane fade" id="idea" role="tabpanel" aria-labelledby="idea-tab">
                        <?php include('../startup_profile/ideas/view_ideas.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="loginModalLabel">Hold up!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You need to be logged in to start a chat.
      </div>
      <div class="modal-footer">
        <a href="../authentication/login.php" class="btn btn-primary">Login</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Not now</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

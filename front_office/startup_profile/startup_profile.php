<?php 
session_start();
include('../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$email = $_SESSION['email'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($result);

$user_id = $user['user_id'];
$result2 = mysqli_query($conn, "SELECT * FROM startups WHERE user_id = $user_id");
$startup = mysqli_fetch_assoc($result2);
$startup_id = $startup['startup_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Startup Profile - Masar Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { margin-bottom: 20px; }
        .profile-info { background-color: #f8f9fa; padding: 20px; border-radius: 8px; }
        .media-preview img, .media-preview video { max-width: 100%; max-height: 300px; margin: 5px 0; }
        .badge { font-size: 0.8rem; }
    </style>
</head>
<body>
<?php include('../components/navbar.php'); ?>

<div class="container mt-4" style="padding-left: 200px; padding-right: 200px;">
    <h2 class="text-center my-4">Welcome, <?php echo htmlspecialchars($startup['startup_name']); ?>!</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="profile-info mb-4">
                <?php if (!empty($startup['logo'])): ?>
                    <div class="text-center mb-3">
                        <img src="../uploads/<?php echo htmlspecialchars($startup['logo']); ?>" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                <?php endif; ?>

                <h4>Contact Info</h4>
                <?php if (!empty($startup['contact_email'])): ?>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($startup['contact_email']); ?></p>
                <?php endif; ?>
                <?php if (!empty($startup['phone_number'])): ?>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($startup['phone_number']); ?></p>
                <?php endif; ?>
                <?php if (!empty($startup['address'])): ?>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($startup['address']); ?></p>
                <?php endif; ?>
                <?php if (!empty($startup['website_url'])): ?>
                    <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($startup['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($startup['website_url']); ?></a></p>
                <?php endif; ?>

                <h4 class="mt-3">About</h4>
                <p><?php echo nl2br(htmlspecialchars($startup['about_section'])); ?></p>

                <h4 class="mt-3">Social</h4>
                <ul>
                    <?php if (!empty($startup['facebook_link'])): ?>
                        <li><a href="<?php echo htmlspecialchars($startup['facebook_link']); ?>" target="_blank">Facebook</a></li>
                    <?php endif; ?>
                    <?php if (!empty($startup['linkedin_link'])): ?>
                        <li><a href="<?php echo htmlspecialchars($startup['linkedin_link']); ?>" target="_blank">LinkedIn</a></li>
                    <?php endif; ?>
                    <?php if (!empty($startup['x_link'])): ?>
                        <li><a href="<?php echo htmlspecialchars($startup['x_link']); ?>" target="_blank">X</a></li>
                    <?php endif; ?>
                    <?php if (!empty($startup['instagram_link'])): ?>
                        <li><a href="<?php echo htmlspecialchars($startup['instagram_link']); ?>" target="_blank">Instagram</a></li>
                    <?php endif; ?>
                </ul>

                <div class="text-center mt-3">
                    <a href="../includes/update_profile.php" class="btn btn-warning">Edit Profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <ul class="nav nav-tabs" id="createTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="post-tab" data-bs-toggle="tab" href="#post" role="tab" aria-controls="post" aria-selected="true">Posts</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="idea-tab" data-bs-toggle="tab" href="#idea" role="tab" aria-controls="idea" aria-selected="false">Ideas</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="solution-tab" data-bs-toggle="tab" href="#solution" role="tab" aria-controls="solution" aria-selected="false">Proposed Solutions</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="createTabsContent">
                <div class="tab-pane fade show active" id="post" role="tabpanel" aria-labelledby="post-tab">
                    <?php include('posts/posts.php'); ?>
                </div>
                <div class="tab-pane fade" id="idea" role="tabpanel" aria-labelledby="idea-tab">
                    <?php include('ideas/ideas.php'); ?>
                </div>
                <div class="tab-pane fade" id="solution" role="tabpanel" aria-labelledby="solution-tab">
                    <?php include('solutions.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

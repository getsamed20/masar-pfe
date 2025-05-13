<?php 
session_start();
include('../includes/db.php');


if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

$user_id = $user['user_id'];
$query2 = mysqli_query($conn, "SELECT * FROM public_institutions WHERE user_id = $user_id");
$institution = mysqli_fetch_assoc($query2);
$institution_id = $institution['institution_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Institution Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { margin-bottom: 20px; }
        .profile-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include('../components/navbar.php'); ?>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($institution['institution_name']); ?>!</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="profile-info mb-4">
                    <?php if (!empty($institution['logo'])): ?>
                        <div class="text-center mb-3">
                            <img src="../uploads/<?php echo htmlspecialchars($institution['logo']); ?>" class="rounded-circle" style="width: 120px; height: 120px;">
                        </div>
                    <?php endif; ?>
                    <h3 class="text-center mb-3"><?php echo htmlspecialchars($institution['institution_name']); ?></h3>

                    <h4>Contact</h4>
                    <p><strong>Email:</strong> <?php echo $institution['contact_email']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $institution['phone_number']; ?></p>
                    <p><strong>Address:</strong> <?php echo $institution['address']; ?></p>
                    <p><strong>Website:</strong> <a href="<?php echo $institution['website_url']; ?>" target="_blank"><?php echo $institution['website_url']; ?></a></p>
                    <h4 class="mt-3">About</h4>
                    <p><?php echo nl2br($institution['about_section']); ?></p>
                    <h4 class="mt-3">Social Media</h4>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo $institution['facebook_link']; ?>" target="_blank">Facebook</a></li>
                        <li><a href="<?php echo $institution['linkedin_link']; ?>" target="_blank">LinkedIn</a></li>
                        <li><a href="<?php echo $institution['x_link']; ?>" target="_blank">X</a></li>
                        <li><a href="<?php echo $institution['instagram_link']; ?>" target="_blank">Instagram</a></li>
                    </ul>
                    <div class="text-center mt-4">
                        <a href="../includes/update_profile.php" class="btn btn-warning w-100">Edit Profile</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">

            <ul class="nav nav-tabs" id="createTabs" role="tablist">

                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="post-tab" data-bs-toggle="tab" href="#post" role="tab" aria-controls="post" aria-selected="true">posts</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="challenge-tab" data-bs-toggle="tab" href="#challenge" role="tab" aria-controls="challenge" aria-selected="false">challenge</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="event-tab" data-bs-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="false">events</a>
                </li> 
                
                
            </ul>

            <div class="tab-content mt-3" id="createTabsContent">

                <div class="tab-pane fade show active" id="post" role="tabpane1" aria-labelledby="post-tab">
                    <?php include('posts_institution/posts_institution.php'); ?>
                </div>
                <div class="tab-pane fade" id="challenge" role="tabpane1" aria-labelledby="challenge-tab">
                    <?php include('challenges/challenges.php');?>
                </div>
                <div class="tab-pane fade" id="event" role="tabpane1" aria-labelledby="event-tab">
                    <?php include('events/events.php'); ?>
                </div>
                
            </div>

        </div>
        <?php include('../components/footer.php'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
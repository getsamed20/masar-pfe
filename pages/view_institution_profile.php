<?php  
session_start();
include('../includes/db.php');
include('../components/navbar.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid profile link.</div></div>";
    exit;
}

$query = "SELECT * FROM public_institutions WHERE user_id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Profile not found.</div></div>";
    exit;
}

$institution_id = $data['institution_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Masar Platform - Institution Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

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
        <h3 class="text-center mb-3"><?php echo htmlspecialchars($data['institution_name']); ?></h3>
        <?php if (!empty($data['description'])): ?>
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($data['description'])); ?></p>
        <?php endif; ?>

        <h4 class="mt-4">Contact Info</h4>
        <?php if (!empty($data['contact_email'])): ?>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($data['contact_email']); ?></p>
        <?php endif; ?>
        <?php if (!empty($data['phone_number'])): ?>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($data['phone_number']); ?></p>
        <?php endif; ?>
        <?php if (!empty($data['address'])): ?>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($data['address']); ?></p>
        <?php endif; ?>
        <?php if (!empty($data['website_url'])): ?>
            <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($data['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($data['website_url']); ?></a></p>
        <?php endif; ?>

        <h4 class="mt-4">Social</h4>
        <ul class="list-unstyled">
            <?php if (!empty($data['facebook_link'])): ?>
                <li><a href="<?php echo htmlspecialchars($data['facebook_link']); ?>" target="_blank">Facebook</a></li>
            <?php endif; ?>
            <?php if (!empty($data['linkedin_link'])): ?>
                <li><a href="<?php echo htmlspecialchars($data['linkedin_link']); ?>" target="_blank">LinkedIn</a></li>
            <?php endif; ?>
            <?php if (!empty($data['x_link'])): ?>
                <li><a href="<?php echo htmlspecialchars($data['x_link']); ?>" target="_blank">X</a></li>
            <?php endif; ?>
            <?php if (!empty($data['instagram_link'])): ?>
                <li><a href="<?php echo htmlspecialchars($data['instagram_link']); ?>" target="_blank">Instagram</a></li>
            <?php endif; ?>
        </ul>

        <?php if (isset($_SESSION['email'])): ?>
            <a href="../chat/chat.php?id=<?php echo $id; ?>&type=institution" class="btn btn-success mt-3 w-100">Start Chat</a>
        <?php else: ?>
            <button class="btn btn-success mt-3 w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Start Chat</button>
        <?php endif; ?>
    </div>
</div>


        <div class="col-md-8">
            <div class="mt-4">
                <ul class="nav nav-tabs" id="institutionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab">Posts</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="challenges-tab" data-bs-toggle="tab" data-bs-target="#challenges" type="button" role="tab">Challenges</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab">Events</button>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="institutionTabsContent">
                    <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                        <?php include('../public_institution_profile/posts_institution/view_posts_institution.php'); ?>
                    </div>
                    <div class="tab-pane fade" id="challenges" role="tabpanel" aria-labelledby="challenges-tab">
                        <?php include('../public_institution_profile/challenges/view_challenges.php'); ?>
                    </div>
                    <div class="tab-pane fade" id="events" role="tabpanel" aria-labelledby="events-tab">
                        <?php include('../public_institution_profile/events/view_events.php'); ?>
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
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You need to log in to start chatting with this institution. 🚀
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

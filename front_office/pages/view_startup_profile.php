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
    <title>Startup Profile - Masar Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
      body {
            background-color: #F2F6FF !important;
  font-family: 'IBM Plex Sans', sans-serif  !important;;
}</style>
    <style>
        .navbar { margin-bottom: 20px; }
        .profile-info {
            background-color: #f8f9fa;
            padding: 0;
            border-radius: 30px;
            height: 800px;
            position: relative;
        }
        .media-preview img, .media-preview video {
            max-width: 100%;
            max-height: 300px;
            margin: 5px 0;
        }
        .content-section {
            margin-top: 20px;
        }

        /* Section buttons */
        .section-btn {
            background: none;
            border: none;
            color: grey;
            font-weight: 500;
            padding: 6px 12px;
            cursor: pointer;
            text-decoration: none;
        }

        .section-btn.active,
        .section-btn:hover,
        .section-btn:focus {
            color: #0C1BA3;
        }

        /* Create Button */
        .btn-create {
            color: #0C1BA3;
            border: 1px solid #0C1BA3;
            background-color: transparent;
        }

        .btn-create:hover,
        .btn-create:focus,
        .btn-create:active {
            color: white;
            background-color: #0C1BA3;
            border-color: #0C1BA3;
        }

        /* Social Media Icons */
        .social-icons-profile {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            padding-left: 0;
        }

        .social-icons-profile a {
            display: inline-block;
        }

        .social-icons-profile img {
            width: 30px;
            height: 30px;
            object-fit: cover;
        }

        .edit-btn-fixed {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

<div class="container mt-4 ps-lg-5 pe-lg-5 ps-xl-5 pe-xl-5">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-info mb-4">
                <div class="top-child text-center" style="height: 198px; width: 100%; background-image: url('../images/account_bg.png'); background-size: cover; background-position: center; position: relative; border-radius: 30px 30px 0 0;">
                    <?php if (!empty($data['logo'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($data['logo']); ?>" 
                             class="rounded-circle" 
                             style="
                                width: 123px; 
                                height: 123px; 
                                object-fit: cover; 
                                position: absolute; 
                                bottom: -60px; 
                                left: 50%; 
                                transform: translateX(-50%);
                            ">
                    <?php endif; ?>
                </div>

                <div style="height: 45px;"></div>
                <div class="text-center mt-5 mb-4">
                    <h2><?php echo htmlspecialchars($data['startup_name']); ?></h2>
                </div>
                <div class="mx-4">
                    <h4 class="mt-3">About</h4>
                    <p><?php echo nl2br(htmlspecialchars($data['about_section'])); ?></p>

                    <?php if (!empty($data['address'])): ?>
                        <p><img src="../pages/icons/pin.png" alt="address" style="width: 23px;"/>    <?php echo htmlspecialchars($data['address']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($data['phone_number'])): ?>
                        <p><img src="../pages/icons/Phone.png" alt="phone" style="width: 24px;"/>     <?php echo htmlspecialchars($data['phone_number']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($data['contact_email'])): ?>
                        <p><img src="../pages/icons/mail.png" alt="email" style="width: 22px;"/>      <?php echo htmlspecialchars($data['contact_email']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($data['website_url'])): ?>
                        <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($data['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($data['website_url']); ?></a></p>
                    <?php endif; ?>

                    <div class="social-icons-profile">
                        <?php if (!empty($data['facebook_link'])): ?>
                            <a href="<?php echo htmlspecialchars($data['facebook_link']); ?>" target="_blank">
                                <img src="../pages/icons/facebook_blue.png" alt="Facebook" />
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($data['linkedin_link'])): ?>
                            <a href="<?php echo htmlspecialchars($data['linkedin_link']); ?>" target="_blank">
                                <img src="../pages/icons/linkedin_blue.png" alt="LinkedIn" />
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($data['instagram_link'])): ?>
                            <a href="<?php echo htmlspecialchars($data['instagram_link']); ?>" target="_blank">
                                <img src="../pages/icons/insta_blue.png" alt="Instagram" />
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="edit-btn-fixed mb-3 text-center">
                        <?php if (isset($_SESSION['email'])): ?>
                            <a href="../chat/chat.php?id=<?php echo $id; ?>&type=startup" class="btn btn-success">Start Chat</a>
                        <?php else: ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Start Chat</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="section-btn active" onclick="showSection('post')">Posts</button>
                    <button class="section-btn" onclick="showSection('idea')">Ideas</button>
                </div>
            </div>

            <div id="post-section" class="content-section">
                <?php include('../startup_profile/posts/view_posts.php'); ?>
            </div>
            <div id="idea-section" class="content-section d-none">
                <?php include('../startup_profile/ideas/view_ideas.php'); ?>
            </div>

        </div>
    </div>
</div>

<script>
    function showSection(section) {
        const sections = ['post', 'idea'];
        sections.forEach(id => {
            document.getElementById(`${id}-section`).classList.add('d-none');
            document.querySelector(`.section-btn[onclick="showSection('${id}')"]`).classList.remove('active');
        });

        document.getElementById(`${section}-section`).classList.remove('d-none');
        document.querySelector(`.section-btn[onclick="showSection('${section}')"]`).classList.add('active');
    }
</script>

<?php include('../components/footer.php'); ?>

<!-- Login Modal -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5

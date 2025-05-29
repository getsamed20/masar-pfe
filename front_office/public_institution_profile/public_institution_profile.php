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
$result2 = mysqli_query($conn, "SELECT * FROM public_institutions WHERE user_id = $user_id");
$institution = mysqli_fetch_assoc($result2);
$institution_id = $institution['institution_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Institution Profile - Masar Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { margin-bottom: 20px; }
        .profile-info { background-color: #f8f9fa; padding: 20px; border-radius: 30px; height: 800px; padding: 0; }
        .media-preview img, .media-preview video { max-width: 100%; max-height: 300px; margin: 5px 0; }
        .content-section {margin-top: 20px;}
        .section-btn {
            background: none;
            border: none;
            color: #0d6efd;
            font-weight: 500;
            padding: 6px 12px;
            cursor: pointer;
        }
        .section-btn:hover, .section-btn:focus, .section-btn:active {
            color: #d63384;
            text-decoration: underline;
            outline: none;
        }
        .btn-outline-pink {
            color: #d63384;
            border: 1px solid #d63384;
            background-color: transparent;
        }
        .btn-outline-pink:hover, .btn-outline-pink:focus, .btn-outline-pink:active {
            color: white;
            background-color: #d63384;
            border-color: #d63384;
        }
    </style>
</head>
<body>
<?php include('../components/navbar.php'); ?>


<div class="container mt-4" style="padding-left: 150px; padding-right: 150px;">

    <div class="row">
        <div class="col-md-4">
    <div class="profile-info mb-4" style="height: 800px; background-color: #f8f9fa; position: relative;">

    <div class="top-child text-center" style="height: 198px; width: 100%; background-image: url('../images/account_bg.png'); background-size: cover; background-position: center; position: relative;border-radius: 30px 30px 0 0;">
                    <?php if (!empty($institution['logo'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($institution['logo']); ?>" class="rounded-circle"
                             style="width: 123px; height: 123px; object-fit: cover; position: absolute; bottom: -60px; left: 50%; transform: translateX(-50%);">
                    <?php endif; ?>
                </div>
                <div style="height: 45px;"></div>

                <div class="text-center mt-5 mb-4">
                    <h2><?php echo htmlspecialchars($institution['institution_name']); ?></h2>
                </div>

                <div class="mx-4">
                    <h4 class="mt-3">About</h4>
                    <p><?php echo nl2br(htmlspecialchars($institution['about_section'])); ?></p>

                    <?php if (!empty($institution['address'])): ?>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($institution['address']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($institution['phone_number'])): ?>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($institution['phone_number']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($institution['contact_email'])): ?>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($institution['contact_email']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($institution['website_url'])): ?>
                        <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($institution['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($institution['website_url']); ?></a></p>
                    <?php endif; ?>

                    <ul>
                        <?php if (!empty($institution['facebook_link'])): ?>
                            <li><a href="<?php echo htmlspecialchars($institution['facebook_link']); ?>" target="_blank">Facebook</a></li>
                        <?php endif; ?>
                        <?php if (!empty($institution['linkedin_link'])): ?>
                            <li><a href="<?php echo htmlspecialchars($institution['linkedin_link']); ?>" target="_blank">LinkedIn</a></li>
                        <?php endif; ?>
                        <?php if (!empty($institution['x_link'])): ?>
                            <li><a href="<?php echo htmlspecialchars($institution['x_link']); ?>" target="_blank">X</a></li>
                        <?php endif; ?>
                        <?php if (!empty($institution['instagram_link'])): ?>
                            <li><a href="<?php echo htmlspecialchars($institution['instagram_link']); ?>" target="_blank">Instagram</a></li>
                        <?php endif; ?>
                    </ul>

                    <div class="text-center mt-3">
                        <a href="../includes/update_profile_institution.php" class="btn btn-warning">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="section-btn me-2" onclick="showSection('post')">Posts</button>
                    <button class="section-btn me-2" onclick="showSection('event')">Events</button>
                    <button class="section-btn" onclick="showSection('challenge')">Challenges</button>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-pink dropdown-toggle" type="button" id="createDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        + Create
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="createDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPostModal">Create Post</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addEventModal">Create Event</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addChallengeModal">Create Challenge</a></li>
                    </ul>
                </div>
            </div>

            <div id="post-section" class="content-section">
                <?php include('posts_institution/posts_institution.php'); ?>
            </div>
            <div id="event-section" class="content-section d-none">
                <?php include('events/events.php'); ?>
            </div>
            <div id="challenge-section" class="content-section d-none">
                <?php include('challenges/challenges.php'); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="posts_institution/add_post_institution.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPostModalLabel">Share Something</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label mt-3">Content</label>
                    <textarea name="content" class="form-control" rows="4" required></textarea>
                    <label class="form-label mt-3">Media (images/videos)</label>
                    <input type="file" name="media[]" class="form-control" multiple>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Publish Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="events/add_event.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Create New Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2"><label>Cover Photo</label><input type="file" name="event_cover" accept="image/*" class="form-control"></div>
          <div class="mb-2"><label>Title</label><input type="text" name="event_title" class="form-control" required></div>
          <div class="mb-2"><label>Description</label><textarea name="event_description" rows="4" class="form-control" required></textarea></div>
          <div class="mb-2"><label>Date</label><input type="date" name="event_date" class="form-control" required></div>
          <div class="mb-2"><label>Time</label><input type="time" name="event_time" class="form-control" required></div>
          <div class="mb-2"><label>Location</label><input type="text" name="event_location" class="form-control" required></div>
          <div class="mb-2"><label>Type</label>
            <select name="event_type" class="form-select" required>
              <option value="offline">Offline</option>
              <option value="online">Online</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Publish Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addChallengeModal" tabindex="-1" aria-labelledby="addChallengeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
    <form method="post" action="challenges/add_challenge.php" enctype="multipart/form-data">
    <div class="modal-header">
          <h5 class="modal-title" id="addChallengeModalLabel">Create New Challenge</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2"><label>Title</label><input type="text" name="challenge_title" class="form-control" required></div>
          <div class="mb-2"><label>Description</label><textarea name="challenge_description" rows="4" class="form-control" required></textarea></div>
          <div class="mb-2"><label>Deadline</label><input type="date" name="challenge_deadline" class="form-control" required></div>
          
          
          <label for="challenge_category">Category:</label>
<select name="challenge_category" id="challenge_category" class="form-control" required>
    <option value="Operations">Operations</option>
    <option value="Design & Planning">Design & Planning</option>
    <option value="Land Use & Urban Planning">Land Use & Urban Planning</option>
    <option value="Vehicles">Vehicles</option>
    <option value="Automated Enforcement">Automated Enforcement</option>
    <option value="ITS & Data Utilization">ITS & Data Utilization</option>
    <option value="Police Enforcement">Police Enforcement</option>
    <option value="Legislation & Regulations">Legislation & Regulations</option>
    <option value="Training, Awareness & Education">Training, Awareness & Education</option>
    <option value="Other" selected>Other</option>
</select>

          <div class="mb-2"><label>Attach File (PDF, DOC, etc.)</label>
            <input type="file" name="challenge_file" class="form-control">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Publish Challenge</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('../components/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSection(section) {
        document.getElementById('post-section').classList.add('d-none');
        document.getElementById('event-section').classList.add('d-none');
        document.getElementById('challenge-section').classList.add('d-none');

        document.getElementById(section + '-section').classList.remove('d-none');
    }
</script>
</body>
</html>

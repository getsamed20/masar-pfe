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
        /* Shared Styles from Startup Profile */
        .masar-create-btn {
            display: block;
            margin: 0 auto;
            background-color: #02FA72;
            color: #0C1BA3;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            width: 200px;
        }

        .masar-create-btn:hover {
            background-color: #01db62;
            color: #091470;
        }

        .navbar { margin-bottom: 20px; }
        .profile-info {
            background-color: #f8f9fa;
            padding: 0;
            border-radius: 30px;
            height: 800px; /* This height might need adjustment based on content */
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

        /* Section Button Styles */
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
            color: #0C1BA3; /* Blue color for active/hover */
        }

        /* Create Button Styles */
        .btn-create {
            display: block;
            background-color: #02FA72;
            color: #0C1BA3;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
        }

        .btn-create:hover {
            background-color: #01db62;
            color: #091470;
        }

        /* Social Icons Profile Styles */
        .social-icons-profile {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            padding-left: 0;
            list-style: none; /* Remove default list styling */
        }

        .social-icons-profile a {
            display: inline-block;
        }

        .social-icons-profile img {
            width: 30px;
            height: 30px;
            object-fit: cover;
        }

        /* Edit Button Fixed Position */
        .edit-btn-fixed {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%; /* Ensure it spans the width of its parent for centering */
        }
         .edit-btn-fixed a {
            display: block;
            margin-bottom: 10px; /* Space between the two links */
        }
    </style>
</head>
<body>
<?php include('../components/navbar.php'); ?>

<div class="container mt-4 ps-lg-5 pe-lg-5 ps-xl-5 pe-xl-5">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-info mb-4">
                <div class="top-child text-center" style="height: 198px; width: 100%; background-image: url('../images/account_bg.png'); background-size: cover; background-position: center; position: relative; border-radius: 30px 30px 0 0;">
                    <?php if (!empty($institution['logo'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($institution['logo']); ?>"
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
                    <h2><?php echo htmlspecialchars($institution['institution_name']); ?></h2>
                </div>
                <div class="mx-4">
                    <h4 class="mt-3">About</h4>
                    <p><?php echo nl2br(htmlspecialchars($institution['about_section'])); ?></p>

                    <?php if (!empty($institution['address'])): ?>
                        <p><img src="../pages/icons/pin.png" alt="adress" style="width: 23px;"/>    <?php echo htmlspecialchars($institution['address']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($institution['phone_number'])): ?>
                        <p><img src="../pages/icons/Phone.png" alt="phone" style="width: 24px;"/>     <?php echo htmlspecialchars($institution['phone_number']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($institution['contact_email'])): ?>
                        <p><img src="../pages/icons/mail.png" alt="email" style="width: 22px;"/>      <?php echo htmlspecialchars($institution['contact_email']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($institution['website_url'])): ?>
                        <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($institution['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($institution['website_url']); ?></a></p>
                    <?php endif; ?>

                    <div class="social-icons-profile">
                        <?php if (!empty($institution['facebook_link'])): ?>
                            <a href="<?php echo htmlspecialchars($institution['facebook_link']); ?>" target="_blank">
                                <img src="../pages/icons/facebook_blue.png" alt="Facebook" />
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($institution['linkedin_link'])): ?>
                            <a href="<?php echo htmlspecialchars($institution['linkedin_link']); ?>" target="_blank">
                                <img src="../pages/icons/linkedin_blue.png" alt="LinkedIn" />
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($institution['instagram_link'])): ?>
                            <a href="<?php echo htmlspecialchars($institution['instagram_link']); ?>" target="_blank">
                                <img src="../pages/icons/insta_blue.png" alt="Instagram" />
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($institution['x_link'])): ?>
                            <a href="<?php echo htmlspecialchars($institution['x_link']); ?>" target="_blank">
                                <img src="../pages/icons/twitter_blue.png" alt="X" /> </a>
                        <?php endif; ?>
                    </div>

                    <div class="edit-btn-fixed mb-3 text-center">
                        <a href="../includes/update_profile_institution.php" class="btn btn-primary" style="background-color: #0C1BA3;  color: white;">Edit Profile Infos</a> </br>
                        <div>
                            <img src="../pages/icons/logout.svg" alt="Logout" style="width: 16px; vertical-align: middle; margin-right: 5px;">
                            <a href="../authentication/logout.php" style="color: #F13E3E; font-weight: bold; text-decoration: none;">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="section-btn active" data-section="post">Posts</button>
                    <button class="section-btn" data-section="event">Events</button>
                    <button class="section-btn" data-section="challenge">Challenges</button>
                </div>
                <div class="dropdown">
                    <button class="btn-create dropdown-toggle" type="button" id="createDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionButtons = document.querySelectorAll('.section-btn');
        const contentSections = document.querySelectorAll('.content-section');

        // Function to activate a section
        function activateSection(sectionName) {
            sectionButtons.forEach(btn => {
                if (btn.getAttribute('data-section') === sectionName) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            contentSections.forEach(section => {
                if (section.id === sectionName + '-section') {
                    section.classList.remove('d-none');
                } else {
                    section.classList.add('d-none');
                }
            });
        }

        // Handle initial section display based on URL or default to 'post'
        const urlParams = new URLSearchParams(window.location.search);
        const activeSectionFromUrl = urlParams.get('section');

        if (activeSectionFromUrl) {
            activateSection(activeSectionFromUrl);
        } else {
            // Default to 'post' if no section parameter is present
            activateSection('post');
        }

        // Add event listeners for section buttons
        sectionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetSection = this.getAttribute('data-section');
                activateSection(targetSection);

                // Update URL without reloading the page (HTML5 History API)
                history.pushState(null, '', `?section=${targetSection}`);
            });
        });
    });
</script>


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
</body>
</html>

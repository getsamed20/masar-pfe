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
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Hebrew:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
 body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            
        }
        </style>

    <style>
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

                .btn-create {
            display: block;
            margin: 0 auto;
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


<?php include('../components/navbar.php'); ?>

<div class="container mt-4 ps-lg-5 pe-lg-5 ps-xl-5 pe-xl-5">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-info mb-4">
                <div class="top-child text-center" style="height: 198px; width: 100%; background-image: url('../images/account_bg.png'); background-size: cover; background-position: center; position: relative; border-radius: 30px 30px 0 0;">
                    <?php if (!empty($startup['logo'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($startup['logo']); ?>" 
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
                    <h2><?php echo htmlspecialchars($startup['startup_name']); ?></h2>
                </div>
                <div class="mx-4">
                    <h4 class="mt-3">About</h4>
                    <p><?php echo nl2br(htmlspecialchars($startup['about_section'])); ?></p>

                    <?php if (!empty($startup['address'])): ?>
                        <p><img src="../pages/icons/pin.png" alt="adress" style="width: 23px;"/>    <?php echo htmlspecialchars($startup['address']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($startup['phone_number'])): ?>
                        <p><img src="../pages/icons/Phone.png" alt="phone" style="width: 24px;"/>     <?php echo htmlspecialchars($startup['phone_number']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($startup['contact_email'])): ?>
                        <p><img src="../pages/icons/mail.png" alt="email" style="width: 22px;"/>      <?php echo htmlspecialchars($startup['contact_email']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($startup['website_url'])): ?>
                        <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($startup['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($startup['website_url']); ?></a></p>
                    <?php endif; ?>
<div class="social-icons-profile">
    <?php if (!empty($startup['facebook_link'])): ?>
        <a href="<?php echo htmlspecialchars($startup['facebook_link']); ?>" target="_blank">
            <img src="../pages/icons/facebook_blue.png" alt="Facebook" />
        </a>
    <?php endif; ?>

    <?php if (!empty($startup['linkedin_link'])): ?>
        <a href="<?php echo htmlspecialchars($startup['linkedin_link']); ?>" target="_blank">
            <img src="../pages/icons/linkedin_blue.png" alt="LinkedIn" />
        </a>
    <?php endif; ?>

    <?php if (!empty($startup['instagram_link'])): ?>
        <a href="<?php echo htmlspecialchars($startup['instagram_link']); ?>" target="_blank">
            <img src="../pages/icons/insta_blue.png" alt="Instagram" />
        </a>
    <?php endif; ?>
</div>

    <div class="edit-btn-fixed mb-3 text-center">
        <a href="../includes/update_profile.php" class="btn btn-primary" style="background-color: #0C1BA3;  color: white;">Edit Profile Infos</a> </br>
        <div><img src="../pages/icons/logout.svg" alt="Logout" style="width: 16px; vertical-align: middle; margin-right: 5px;">
    <a href="../authentication/logout.php" style="color: #F13E3E; font-weight: bold; text-decoration: none;">
        Logout
    </a></div>
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
                    <button class="section-btn" onclick="showSection('solution')">Proposed Solutions</button>
                </div>
                <div class="dropdown">
                    <button class="btn-create dropdown-toggle" type="button" id="createDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        + Create
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="createDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPostModal">Create Post</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addIdeaModal">Create Idea</a></li>
                    </ul>
                </div>
            </div>
             <div id="solution-section" class="content-section d-none">
                <?php include('./solutions.php'); ?>
            </div>

            <div id="post-section" class="content-section">
                <?php include('posts/posts.php'); ?>
            </div>
            <div id="idea-section" class="content-section d-none">
                <?php include('ideas/ideas.php'); ?>
            </div>
           
        </div>
    </div>
</div>

<script>
    function showSection(section) {
        const sections = ['post', 'idea', 'solution'];
        sections.forEach(id => {
            document.getElementById(`${id}-section`).classList.add('d-none');
            document.querySelector(`.section-btn[onclick="showSection('${id}')"]`).classList.remove('active');
        });

        document.getElementById(`${section}-section`).classList.remove('d-none');
        document.querySelector(`.section-btn[onclick="showSection('${section}')"]`).classList.add('active');
    }
</script>




<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="posts/add_post.php" method="post" enctype="multipart/form-data">
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

<!-- Add Idea Modal -->
<div class="modal fade" id="addIdeaModal" tabindex="-1" aria-labelledby="addIdeaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="ideas/add_idea.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIdeaModalLabel">Propose a New Idea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>

                    <label class="form-label mt-3">Description</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>

                    <label class="form-label mt-3">Attachments (images/videos)</label>
                    <input type="file" name="media[]" class="form-control" multiple>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit Idea</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include('../components/footer.php'); ?>

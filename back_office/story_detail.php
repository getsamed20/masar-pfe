<?php
session_start();
include('db.php');

// Security checks
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_publish_success.php"); // Fixed redirect
    exit();
}

$story_id = $_GET['id'];
$story = mysqli_query($conn, "SELECT * FROM success_stories WHERE story_id = $story_id");
$story = mysqli_fetch_assoc($story);

if (!$story) {
    header("Location: admin_publish_success.php"); // Fixed redirect
    exit();
}

// Handle delete
if (isset($_POST['delete'])) {
    // Delete media first
    mysqli_query($conn, "DELETE FROM media WHERE story_id = $story_id");
    // Then delete story
    mysqli_query($conn, "DELETE FROM success_stories WHERE story_id = $story_id");
    header("Location: admin_publish_success.php"); // Fixed redirect after delete
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($story['title']); ?> | Story Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .story-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .story-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .story-title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 700;
            font-size: 36px;
            color: #0C1BA3;
            margin-bottom: 30px;
        }
        .section-title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 700;
            font-size: 22px;
            color: #000000;
            margin: 30px 0 15px 0;
        }
        .section-content {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 400;
            font-size: 20px;
            color: #000000;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        .media-item {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .media-item img, .media-item video {
            width: 100%;
            height: auto;
            display: block;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .back-btn {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 500;
            font-size: 14px;
            color: #0C1BA3;
            border: 1px solid #0C1BA3;
            border-radius: 4px;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s;
        }
        .back-btn:hover {
            background-color: #0C1BA3;
            color: white;
        }
        .delete-btn {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 500;
            font-size: 14px;
            color: white;
            background-color: #dc3545;
            border: 1px solid #dc3545;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .delete-btn:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .modal-content {
            border-radius: 10px;
        }
        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }
        .modal-title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 700;
            color: #0C1BA3;
        }
        .modal-body {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>
    
    <div class="main-content">
        <div class="story-container">
            <div class="button-group">
                <a href="admin_publish_success.php" class="back-btn">← Go Back</a> <!-- Fixed URL -->
                <button class="delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Story</button>
            </div>

            <div class="story-header">
                <h1 class="story-title"><?php echo htmlspecialchars($story['title']); ?></h1>
            </div>

            <h2 class="section-title">Synopsis</h2>
            <div class="section-content">
                <?php 
                // Extract first paragraph as synopsis
                $paragraphs = explode("\n", $story['content']);
                echo nl2br(htmlspecialchars($paragraphs[0])); 
                ?>
            </div>

            <h2 class="section-title">Story</h2>
            <div class="section-content">
                <?php echo nl2br(htmlspecialchars($story['content'])); ?>
            </div>

            <?php
            $mediaResult = mysqli_query($conn, "SELECT * FROM media WHERE story_id = $story_id ORDER BY media_id");
            if (mysqli_num_rows($mediaResult) > 0): ?>
                <div class="media-grid">
                    <?php while ($item = mysqli_fetch_assoc($mediaResult)): ?>
                        <div class="media-item">
                            <?php if ($item['media_type'] === 'image'): ?>
                                <img src="<?php echo htmlspecialchars($item['file_path']); ?>">
                            <?php elseif ($item['media_type'] === 'video'): ?>
                                <video controls>
                                    <source src="<?php echo htmlspecialchars($item['file_path']); ?>">
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to permanently delete this story? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="back-btn" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$story_id; ?>">
                        <button type="submit" name="delete" class="delete-btn">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
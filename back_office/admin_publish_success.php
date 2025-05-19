<?php 
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('admin_navbar.php'); ?>

  <meta charset="UTF-8">
  <title>Publish Success Story</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('admin_navbar.php'); ?>
<div class="container py-4">

  <h2>Publish a New Success Story</h2>

  <form action="submit_success_story.php" method="POST" enctype="multipart/form-data" class="mb-5">
    <input type="text" name="title" class="form-control mb-2" placeholder="Story Title" required>
    <textarea name="content" class="form-control mb-2" rows="6" placeholder="Story Content" required></textarea>
    
    <label>Upload Images/Videos (you can select multiple)</label>
    <input type="file" name="media_files[]" multiple accept="image/*,video/*" class="form-control mb-3">
    
    <button type="submit" class="btn btn-success">Publish</button>
  </form>

  <h3 class="mb-3">Published Stories</h3>
  <?php include 'success_stories.php'; ?>
</div>
</body>
</html>

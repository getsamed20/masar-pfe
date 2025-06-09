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
  <meta charset="UTF-8">
  <title>Publish Success Story</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{
      background-color: #F2F6FF !important;
    }
    .add-story-card {
      width: 100%;
      height: 200px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.3s ease;
      margin-bottom: 30px;
    }
    .add-story-card:hover {
      transform: translateY(-5px);
    }
    .add-story-card img {
      width: 60px;
      height: 60px;
    }
    .main-content {
      margin-left: 250px; /* Adjust based on your navbar width */
      padding: 20px;
    }
    .container {
      max-width: 1500px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <?php include('admin_navbar.php'); ?>
  
  <div class="main-content">
    
    <div class="container py-4">
      <!-- Add Story Card -->
      <div class="add-story-card" onclick="window.location.href='add_story.php'">
        <img src="images/plus-icon.png" alt="Add Story">
      </div>

      <h3 class="mb-3">Published Stories</h3>
      <?php include 'success_stories.php'; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
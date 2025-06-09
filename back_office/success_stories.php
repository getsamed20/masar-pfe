<?php
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
  <title>Published Stories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'IBM Plex Sans', sans-serif;
    }
    .story-card {
      background: white;
      border-radius: 30px;
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
      height: 380px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      width: 100%;
      margin-bottom: 20px;
    }
    .story-title {
      font-weight: 700;
      font-size: 16px;
      color: #0C1BA3;
      margin-bottom: 16px;
    }
    .story-description {
      font-weight: 400;
      font-size: 14px;
      color: #000;
      flex-grow: 1;
      overflow: hidden;
      position: relative;
      line-height: 1.5;
      display: -webkit-box;
      -webkit-line-clamp: 7;
      -webkit-box-orient: vertical;
      word-wrap: break-word;
    }
    .learn-more-btn {
      font-weight: 500;
      font-size: 14px;
      color: #0C1BA3;
      border: 1px solid #0C1BA3;
      border-radius: 4px;
      padding: 8px 16px;
      text-decoration: none;
      transition: all 0.3s ease;
      margin-top: auto;
      width: fit-content;
    }
    .learn-more-btn:hover {
      background-color: #0C1BA3;
      color: white;
    }
    .arrow-icon {
      margin-left: 8px;
    }
  </style>
</head>
<body>

<div class="container py-4">
  <div class="row">
    <?php
    $stories = mysqli_query($conn, "SELECT * FROM success_stories ORDER BY created_at DESC");

    while ($story = mysqli_fetch_assoc($stories)) {
        $story_id = $story['story_id'];
        $title = htmlspecialchars($story['title']);
        $content = htmlspecialchars($story['content']);

        $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $preview = implode(' ', array_slice($sentences, 0, 8));

        echo '<div class="col-lg-4 col-md-6">';
        echo '  <div class="story-card">';
        echo '    <h3 class="story-title">' . $title . '</h3>';
        echo '    <p class="story-description">' . $preview . '</p>';
        echo '    <a href="story_detail.php?id=' . $story_id . '" class="learn-more-btn">Learn more <span class="arrow-icon">→</span></a>';
        echo '  </div>';
        echo '</div>';
    }
    ?>
  </div>
</div>

</body>
</html>

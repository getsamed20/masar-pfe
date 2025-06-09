<?php 
session_start();
include '../includes/db.php';

$categories = [
    "Statistics & Reports",
    "Laws & Regulations",
    "Innovation & Technology",
    "Case Studies & Projects",
    "Research & Publications",
    "Guides & Toolkit"
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Document Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Hebrew:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
 body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            
        }
        </style>
    <style>
                .blue-title{color:#0C1BA3}

        .category-card {
            width: 400px;
            height: 560px;
            background-color: #0C1BA3;
            color: white;
            border-radius: 16px;
            text-align: center;
            text-decoration: none;
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-icon {
            width: 120px;
            height: 120px;
            margin-bottom: 30px;
        }

        .category-title {
            font-size: 1.3rem;
            font-weight: bold;
            padding: 0 20px;
        }

        a.category-link {
            text-decoration: none;
        }
    </style>
</head>
<body>
<?php include('../components/navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center blue-title">Knowledge Hub</h2>
    <p class="mb-4 text-center text-muted">
  We provided curated documents and resources to help startups, institutions,<br>
  and curious minds explore the world of road safety and smart mobility.
</p>

    <div class="row justify-content-center">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4 d-flex justify-content-center mb-4">
                <a href="documents_by_category.php?category=<?= urlencode($category) ?>" class="category-link">
                    <div class="category-card shadow">
                        <img src="icons/category.svg" alt="Category Icon" class="category-icon">
                        <div class="category-title"><?= htmlspecialchars($category) ?></div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('../components/footer.php'); ?>
</body>
</html>

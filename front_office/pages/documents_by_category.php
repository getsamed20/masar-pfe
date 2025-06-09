<?php 
session_start();
include '../includes/db.php';

$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

if (empty($category)) {
    header("Location: documents.php");
    exit();
}

$sql = "SELECT * FROM documents WHERE category = '$category' ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($category) ?> Documents</title>
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
        .doc-card {
            background-color: #0C1BA3;
            color: white;
            width: 300px;
            height: 50px;
            border-radius: 10px;
            text-align: center;
            line-height: 50px;
            font-size: 1rem;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .doc-card:hover {
            transform: translateY(-3px);
        }

        .doc-container {
            display: flex;
            flex-wrap: wrap;
            column-gap: 80px;
            row-gap: 30px;
            padding: 20px 0;
        }

        @media (max-width: 768px) {
            .doc-container {
                column-gap: 40px;
                justify-content: flex-start;
            }
        }
        
    </style>
</head>
<body>
<?php include('../components/navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center blue-title"><?= htmlspecialchars($category) ?> Documents</h2>
    <p class="mb-4 text-center text-muted">
  Official data, accident reports, yearly road safety summaries.
</p>
    <div class="doc-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                    $file_url = '../../back_office/docs_uploads/' . basename($row['file_path']);
                    $file_ext = pathinfo($row['file_path'], PATHINFO_EXTENSION);
                    $file_title = $row['title'] . '.' . $file_ext;
                ?>
                <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="doc-card">
                    <?= htmlspecialchars($file_title) ?> <img src="icons/download.svg" style="width: 20px; height: 20px;" >
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert  w-100">No documents available in this category.</div>
        <?php endif; ?>
    </div>


</div>

<?php include('../components/footer.php'); ?>
</body>
</html>

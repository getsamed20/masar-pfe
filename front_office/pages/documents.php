<?php
session_start();

include '../includes/db.php';

$sql = "SELECT * FROM documents ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('../components/navbar.php'); ?>

<div class="container mt-5">
    <h2>Available Documents</h2>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                <?php $file_url = '../../back_office/docs_uploads/' . basename($row['file_path']); ?>
                <a href="<?= htmlspecialchars($file_url) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Download</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php include('../components/footer.php'); ?>

</body>
</html>

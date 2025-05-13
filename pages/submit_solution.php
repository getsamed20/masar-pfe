<?php
include '../includes/db.php';
session_start();

$challenge_id = isset($_GET['challenge_id']) ? intval($_GET['challenge_id']) : 0;
$startup_id = $_SESSION['startup_id']; 
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $filePath = '';

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/solutions/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['attachment']['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        move_uploaded_file($_FILES['attachment']['tmp_name'], $filePath);
    }

    $query = "INSERT INTO solutions (challenge_id, startup_id, proposal_title, proposal_description, file_attachment)
              VALUES ('$challenge_id', '$startup_id', '$title', '$description', '$filePath')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Solution submitted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Submit Solution</h1>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Solution Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Solution Description</label>
            <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="attachment" class="form-label">Attach a File (optional)</label>
            <input type="file" name="attachment" id="attachment" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Submit Solution</button>
        <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

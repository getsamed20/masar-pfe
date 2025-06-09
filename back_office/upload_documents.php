<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document_file'])) {
    $admin_id = mysqli_real_escape_string($conn, $_SESSION['admin_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $file = $_FILES['document_file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    $upload_dir = 'docs_uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . uniqid() . '.' . $file_ext;

    if (move_uploaded_file($file_tmp, $file_path)) {
        $sql = "INSERT INTO documents (admin_id, title, description, category, file_path)
                VALUES ('$admin_id', '$title', '$description', '$category', '$file_path')";
        if (mysqli_query($conn, $sql)) {
            $message = "Document uploaded successfully!";
        } else {
            $message = "Database error: " . mysqli_error($conn);
        }
    } else {
        $message = "Failed to upload the file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Upload Document</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <select name="category" class="form-select" required>
                <option value="" disabled selected>Select a category</option>
                <option value="Statistics & Reports">Statistics and Reports</option>
                <option value="Laws & Regulations">Laws and Regulations</option>
                <option value="Innovation & Technology">Innovation and Technology</option>
                <option value="Case Studies & Projects">Case Studies and Projects</option>
                <option value="Research & Publications">Research and Publications</option>
                <option value="Guides & Toolkits">Guides and Toolkits</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Select File</label>
            <input type="file" name="document_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <hr>
    <h4 class="mt-4">Uploaded Documents</h4>
    <?php
    $query = "SELECT * FROM documents ORDER BY uploaded_at DESC";
    $result = mysqli_query($conn, $query);
    while ($doc = mysqli_fetch_assoc($result)):
    ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5><?= htmlspecialchars($doc['title']) ?></h5>
                <p><?= htmlspecialchars($doc['description']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($doc['category']) ?></p>
                <a href="<?= $doc['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Download</a>
                <a href="delete_document.php?id=<?= $doc['document_id'] ?>" 
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Are you sure you want to delete this document?')">
                   Delete
                </a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>

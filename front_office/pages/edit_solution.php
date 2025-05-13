<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'startup') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['solution_id'])) {
    header('Location: ../index.php');
    exit;
}

$solution_id = $_GET['solution_id'];
$startup_id = $_SESSION['startup_id'];

$getSolution = mysqli_query($conn, "SELECT * FROM solutions WHERE solution_id = '$solution_id' AND startup_id = '$startup_id'");
if (!$getSolution || mysqli_num_rows($getSolution) == 0) {
    echo "You don't have access to this solution.";
    exit;
}

$solution = mysqli_fetch_assoc($getSolution);

if (isset($_POST['update_solution'])) {
    $title = mysqli_real_escape_string($conn, $_POST['proposal_title']);
    $description = mysqli_real_escape_string($conn, $_POST['proposal_description']);

    $filePath = $solution['file_attachment']; // Keep current file by default
    if (isset($_FILES['file_attachment']) && $_FILES['file_attachment']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/solutions/';
        $fileName = basename($_FILES['file_attachment']['name']);
        $filePath = $uploadDir . time() . '_' . $fileName;
        move_uploaded_file($_FILES['file_attachment']['tmp_name'], $filePath);
    }

    $update = mysqli_query($conn, "
        UPDATE solutions 
        SET proposal_title = '$title', proposal_description = '$description', file_attachment = '$filePath' 
        WHERE solution_id = '$solution_id' AND startup_id = '$startup_id'
    ");

    if ($update) {
        header("Location: ../pages/solution.php?solution_id=" . $solution_id);
        exit;
    } else {
        echo "Something went wrong updating the solution.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2>Edit Your Solution</h2>
    <form action="" method="post" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label for="proposal_title" class="form-label">Proposal Title</label>
            <input type="text" name="proposal_title" id="proposal_title" class="form-control" required value="<?php echo htmlspecialchars($solution['proposal_title']); ?>">
        </div>
        <div class="mb-3">
            <label for="proposal_description" class="form-label">Proposal Description</label>
            <textarea name="proposal_description" id="proposal_description" rows="5" class="form-control" required><?php echo htmlspecialchars($solution['proposal_description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="file_attachment" class="form-label">Replace File Attachment (optional)</label>
            <input type="file" name="file_attachment" id="file_attachment" class="form-control">
            <?php if (!empty($solution['file_attachment']) && file_exists($solution['file_attachment'])): ?>
                <p class="mt-2">Current File: <a href="<?php echo $solution['file_attachment']; ?>" target="_blank">Download</a></p>
            <?php endif; ?>
        </div>
        <button type="submit" name="update_solution" class="btn btn-primary">Update Solution</button>
        <a href="../pages/solution.php?solution_id=<?php echo $solution_id; ?>" class="btn btn-secondary ms-2">Cancel</a>
        </form>

</body>
</html>

<?php
session_start();
include('../../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$email = $_SESSION['email'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($result);

$user_id = $user['user_id'];
$result2 = mysqli_query($conn, "SELECT * FROM public_institutions WHERE user_id = $user_id");
$institution = mysqli_fetch_assoc($result2);
$institution_id = $institution['institution_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $challenge_title = mysqli_real_escape_string($conn, $_POST['challenge_title']);
    $challenge_description = mysqli_real_escape_string($conn, $_POST['challenge_description']);
    $challenge_deadline = mysqli_real_escape_string($conn, $_POST['challenge_deadline']);
    $challenge_category = mysqli_real_escape_string($conn, $_POST['challenge_category']);

    $query = "INSERT INTO challenges (institution_id, title, description, deadline, category, posted_at, created_at)
              VALUES ('$institution_id', '$challenge_title', '$challenge_description', '$challenge_deadline', '$challenge_category', NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        $challenge_id = mysqli_insert_id($conn); 
        if (isset($_FILES['challenge_file']) && $_FILES['challenge_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['challenge_file']['tmp_name'];
            $fileName = $_FILES['challenge_file']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid('challenge_') . '.' . $fileExtension;
            $uploadDir = '../../uploads/challenges/';
            $destPath = $uploadDir . $newFileName;

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $media_type = mime_content_type($destPath);
                $relativePath = '../../uploads/challenges/' . $newFileName;

                $insertMedia = "INSERT INTO media (challenge_id, media_type, file_path, created_at)
VALUES ('$challenge_id', '$mediaType', '$relativePath', NOW())";
                mysqli_query($conn, $insertMedia);
            }
        }

        header("Location: ../public_institution_profile.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

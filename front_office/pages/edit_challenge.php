<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $challenge_id = mysqli_real_escape_string($conn, $_POST['challenge_id']);
    $title = mysqli_real_escape_string($conn, $_POST['challenge_title']);
    $description = mysqli_real_escape_string($conn, $_POST['challenge_description']);
    $deadline = mysqli_real_escape_string($conn, $_POST['challenge_deadline']);
    $category = mysqli_real_escape_string($conn, $_POST['challenge_category']);
    $file_path = '';
    if (isset($_FILES['challenge_file']) && $_FILES['challenge_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['challenge_file']['tmp_name'];
        $fileName = $_FILES['challenge_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('challenge_', true) . '.' . $fileExtension;
        $uploadFileDir = '../../uploads/challenges/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $file_path = $dest_path;
        }
    }

    $updateQuery = "UPDATE challenges SET title='$title', description='$description', deadline='$deadline', category='$category' WHERE challenge_id='$challenge_id'";


    $result = mysqli_query($conn, $updateQuery);

   if ($result) {
    if (!empty($_FILES['media_files']['name'][0])) {
        $mediaUploadDir = '../../uploads/challenges/media/';
        $mediaRelativeDir = '../uploads/challenges/media/';

        if (!file_exists($mediaUploadDir)) {
            mkdir($mediaUploadDir, 0755, true);
        }

        foreach ($_FILES['media_files']['tmp_name'] as $index => $tmpPath) {
            if ($_FILES['media_files']['error'][$index] === UPLOAD_ERR_OK) {
                $originalName = $_FILES['media_files']['name'][$index];
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $uniqueName = uniqid('media_', true) . '.' . $extension;
                
                $destination = $mediaUploadDir . $uniqueName;
                $relativePath = $mediaRelativeDir . $uniqueName;

                if (move_uploaded_file($tmpPath, $destination)) {
                    $mediaType = mime_content_type($destination); // Better than explode()

                    $insertMedia = "
                        INSERT INTO media (challenge_id, media_type, file_path, created_at)
                        VALUES ('$challenge_id', '$mediaType', '$relativePath', NOW())";
                    mysqli_query($conn, $insertMedia);
                }
            }
        
            }
        }

        header("Location: challenge_details.php?challenge_id=$challenge_id");
        exit;
    } else {
        echo "Error updating challenge: " . mysqli_error($conn);
    }
} else {
    header("Location: ../public_institution_profile.php");
    exit;
}
?>

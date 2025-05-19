<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);

$insertStorySQL = "INSERT INTO success_stories (title, content) VALUES ('$title', '$content')";
mysqli_query($conn, $insertStorySQL);
$story_id = mysqli_insert_id($conn);

$mediaFiles = $_FILES['media_files'];
$uploadDir = 'uploads/success_stories/';

for ($i = 0; $i < count($mediaFiles['name']); $i++) {
    if ($mediaFiles['error'][$i] === UPLOAD_ERR_OK) {
        $originalName = basename($mediaFiles['name'][$i]);
        $uniqueName = uniqid() . '_' . $originalName;
        $filePath = $uploadDir . $uniqueName;
        $tmpPath = $mediaFiles['tmp_name'][$i];

        move_uploaded_file($tmpPath, $filePath);

        $mime = mime_content_type($filePath);
        $mediaType = str_starts_with($mime, 'image') ? 'image' : 'video';

        $insertMediaSQL = "INSERT INTO media (story_id, media_type, file_path) 
                           VALUES ('$story_id', '$mediaType', '$filePath')";
        mysqli_query($conn, $insertMediaSQL);
    }
}

header("Location: admin_publish_success.php?success=1");
exit();
?>

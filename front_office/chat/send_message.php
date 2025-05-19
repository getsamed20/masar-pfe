<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');
$selectedType = $_GET['type'] ?? null;

if (!$receiver_id || ($message === '' && empty($_FILES['attachment']['name']))) {
    die("Missing receiver or message/file.");
}

$message_safe = mysqli_real_escape_string($conn, $message);

$insert_query = "INSERT INTO messages (sender_id, receiver_id, message, sent_at, seen) 
                 VALUES ($sender_id, $receiver_id, '$message_safe', NOW(), 0)";
mysqli_query($conn, $insert_query);
$message_id = mysqli_insert_id($conn);

if (!empty($_FILES['attachment']['name'])) {
    $file_name = $_FILES['attachment']['name'];
    $file_tmp = $_FILES['attachment']['tmp_name'];
    $file_type = $_FILES['attachment']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_img = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_video = ['mp4', 'webm', 'ogg'];
    $allowed_docs = ['pdf', 'docx', 'doc', 'xls', 'xlsx', 'ppt', 'pptx'];

    if (in_array($file_ext, $allowed_img)) {
        $media_type = 'image';
    } elseif (in_array($file_ext, $allowed_video)) {
        $media_type = 'video';
    } elseif (in_array($file_ext, $allowed_docs)) {
        $media_type = 'document';
    } else {
        $media_type = 'other'; 
    }

=    $max_file_size = 10 * 1024 * 1024; 
    if ($_FILES['attachment']['size'] > $max_file_size) {
        die("File size exceeds the maximum limit of 10MB.");
    }

    $upload_folder = '../uploads/';
    $unique_name = uniqid() . '_' . basename($file_name);
    $upload_path = $upload_folder . $unique_name;

    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true); 
    }

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $relative_path = basename($upload_path); 
        $insert_media = "INSERT INTO media_chat (message_id, file_path, media_type, uploaded_at)
                         VALUES ($message_id, '$relative_path', '$media_type', NOW())";
        mysqli_query($conn, $insert_media);
    } else {
        die("Error uploading file.");
    }
}

header("Location: chat.php?id=$receiver_id&type=$selectedType");
exit;
?>

<?php
session_start();
include('../includes/db.php');

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');
$selectedType = $_GET['type'] ?? null;

// Make sure both receiver and at least message or file are provided
if (!$receiver_id || ($message === '' && empty($_FILES['attachment']['name']))) {
    die("Missing receiver or message/file.");
}

// Insert the message into the messages table
$message_safe = mysqli_real_escape_string($conn, $message);
$insert_query = "INSERT INTO messages (sender_id, receiver_id, message, sent_at, seen) 
                 VALUES ($sender_id, $receiver_id, '$message', NOW(), 0)";

mysqli_query($conn, $insert_query);
$message_id = mysqli_insert_id($conn);

// If there's a file uploaded
if (!empty($_FILES['attachment']['name'])) {
    $file_name = $_FILES['attachment']['name'];
    $file_tmp = $_FILES['attachment']['tmp_name'];
    $file_type = $_FILES['attachment']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Determine media type
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

    // Upload path
    $upload_folder = '../uploads/';
    $unique_name = uniqid() . '_' . basename($file_name);
    $upload_path = $upload_folder . $unique_name;

    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }

    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Insert into media_chat table
        $relative_path = basename($upload_path); // Store only the filename
        $insert_media = "INSERT INTO media_chat (message_id, file_path, media_type, uploaded_at)
                         VALUES ($message_id, '$relative_path', '$media_type', NOW())";
        mysqli_query($conn, $insert_media);
    }
}

// Redirect back to chat
header("Location: chat.php?id=$receiver_id&type=$selectedType");
exit;
?>

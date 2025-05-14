<?php
session_start();
include('../includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');
$selectedType = $_GET['type'] ?? null;

// Make sure both receiver and at least message or file are provided
if (!$receiver_id || ($message === '' && empty($_FILES['attachment']['name']))) {
    die("Missing receiver or message/file.");
}

// Sanitize and escape message input
$message_safe = mysqli_real_escape_string($conn, $message);

// Insert the message into the messages table
$insert_query = "INSERT INTO messages (sender_id, receiver_id, message, sent_at, seen) 
                 VALUES ($sender_id, $receiver_id, '$message_safe', NOW(), 0)";
mysqli_query($conn, $insert_query);
$message_id = mysqli_insert_id($conn);

// If there's a file uploaded
if (!empty($_FILES['attachment']['name'])) {
    $file_name = $_FILES['attachment']['name'];
    $file_tmp = $_FILES['attachment']['tmp_name'];
    $file_type = $_FILES['attachment']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Allowed file extensions for images, videos, and documents
    $allowed_img = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_video = ['mp4', 'webm', 'ogg'];
    $allowed_docs = ['pdf', 'docx', 'doc', 'xls', 'xlsx', 'ppt', 'pptx'];

    // Determine media type
    if (in_array($file_ext, $allowed_img)) {
        $media_type = 'image';
    } elseif (in_array($file_ext, $allowed_video)) {
        $media_type = 'video';
    } elseif (in_array($file_ext, $allowed_docs)) {
        $media_type = 'document';
    } else {
        $media_type = 'other'; // Fallback for unsupported types
    }

    // Check file size (example: 10MB max)
    $max_file_size = 10 * 1024 * 1024; // 10MB
    if ($_FILES['attachment']['size'] > $max_file_size) {
        die("File size exceeds the maximum limit of 10MB.");
    }

    // Upload path
    $upload_folder = '../uploads/';
    $unique_name = uniqid() . '_' . basename($file_name);
    $upload_path = $upload_folder . $unique_name;

    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true); // Create the upload folder if it doesn't exist
    }

    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Insert file info into media_chat table
        $relative_path = basename($upload_path); // Store only the filename
        $insert_media = "INSERT INTO media_chat (message_id, file_path, media_type, uploaded_at)
                         VALUES ($message_id, '$relative_path', '$media_type', NOW())";
        mysqli_query($conn, $insert_media);
    } else {
        die("Error uploading file.");
    }
}

// Redirect back to chat
header("Location: chat.php?id=$receiver_id&type=$selectedType");
exit;
?>

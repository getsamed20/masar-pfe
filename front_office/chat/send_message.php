<?php
session_start();
include('../includes/db.php'); // Adjust path as needed

header('Content-Type: application/json'); // Set header for JSON response

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senderId = $_SESSION['user_id'] ?? null;
    $receiverId = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : null;
    $messageContent = trim($_POST['message'] ?? '');

    if (!$senderId || !$receiverId) {
        $response['message'] = "Sender or receiver ID is missing.";
        echo json_encode($response);
        exit;
    }

    if (empty($messageContent) && empty($_FILES['attachment']['name'])) {
        $response['message'] = "Message content or attachment is required.";
        echo json_encode($response);
        exit;
    }

    mysqli_begin_transaction($conn);

    try {
        // Insert message
        $insertMessageQuery = "INSERT INTO messages (sender_id, receiver_id, message, sent_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $insertMessageQuery);
        mysqli_stmt_bind_param($stmt, "iis", $senderId, $receiverId, $messageContent);
        mysqli_stmt_execute($stmt);
        $messageId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        // Handle attachment
        if ($messageId && !empty($_FILES['attachment']['name'])) {
            $targetDir = "../uploads/"; // Make sure this directory exists and is writable
            $fileName = basename($_FILES["attachment"]["name"]);
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = uniqid('chat_') . '.' . $fileExtension;
            $targetFilePath = $targetDir . $newFileName;
            $fileType = '';

            // Determine file type
            $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedVideoTypes = ['mp4', 'mov', 'avi', 'webm'];
            $allowedDocumentTypes = ['pdf', 'doc', 'docx', 'txt'];

            if (in_array($fileExtension, $allowedImageTypes)) {
                $fileType = 'image';
            } elseif (in_array($fileExtension, $allowedVideoTypes)) {
                $fileType = 'video';
            } elseif (in_array($fileExtension, $allowedDocumentTypes)) {
                $fileType = 'document';
            } else {
                // Unsupported file type, roll back transaction
                mysqli_rollback($conn);
                $response['message'] = "Unsupported file type.";
                echo json_encode($response);
                exit;
            }

            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
                $insertMediaQuery = "INSERT INTO media_chat (message_id, file_path, media_type) VALUES (?, ?, ?)";
                $stmtMedia = mysqli_prepare($conn, $insertMediaQuery);
                mysqli_stmt_bind_param($stmtMedia, "iss", $messageId, $newFileName, $fileType);
                mysqli_stmt_execute($stmtMedia);
                mysqli_stmt_close($stmtMedia);
            } else {
                mysqli_rollback($conn);
                $response['message'] = "Failed to upload file.";
                echo json_encode($response);
                exit;
            }
        }

        mysqli_commit($conn);
        $response['status'] = 'success';
        $response['message'] = 'Message sent successfully.';
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $response['message'] = "Database error: " . $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>
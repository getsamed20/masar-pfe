<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $document_id = intval($_GET['id']);

    $query = "SELECT file_path FROM documents WHERE document_id = $document_id AND admin_id = {$_SESSION['admin_id']}";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = $row['file_path'];

        $delete_sql = "DELETE FROM documents WHERE document_id = $document_id AND admin_id = {$_SESSION['admin_id']}";
        if (mysqli_query($conn, $delete_sql)) {
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
}

header("Location: upload_documents.php");
exit();

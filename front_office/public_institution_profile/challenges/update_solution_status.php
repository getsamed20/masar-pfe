<?php
session_start();
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solution_id'], $_POST['status'])) {
    $solution_id = intval($_POST['solution_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $allowedStatuses = ['pending', 'selected', 'rejected'];
    if (!in_array($status, $allowedStatuses)) {
        die('Invalid status value.');
    }

    $update = mysqli_query($conn, "UPDATE solutions SET status = '$status' WHERE solution_id = '$solution_id'");
    
    if ($update) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        echo "Failed to update status.";
    }
} else {
    echo "Invalid request.";
}

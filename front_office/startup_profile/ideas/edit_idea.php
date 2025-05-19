<?php
include '../../includes/db.php';
session_start();

if (!isset($_SESSION['startup_id'])) {
    header("Location: ../login.php");
    exit();
}

$startup_id = $_SESSION['startup_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idea_id = mysqli_real_escape_string($conn, $_POST['idea_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $check = mysqli_query($conn, "SELECT * FROM ideas WHERE idea_id = '$idea_id' AND startup_id = '$startup_id'");
    if (mysqli_num_rows($check) == 0) {
        $_SESSION['error'] = "Unauthorized or idea not found.";
        header("Location: ../ideas.php");
        exit();
    }

    $update = mysqli_query($conn, "UPDATE ideas SET title = '$title', description = '$description' WHERE idea_id = '$idea_id'");

    if ($update) {
        $_SESSION['success'] = "Idea updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update idea. Please try again.";
    }

    header("Location: ideas/ideas.php");
    exit();
} else {
    header("Location: ideas/ideas.php");
    exit();
}

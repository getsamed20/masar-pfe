<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['email'])) {
    die("Access denied. Please log in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_title'])) {
    $event_title = mysqli_real_escape_string($conn, $_POST['event_title']);
    $event_description = mysqli_real_escape_string($conn, $_POST['event_description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $event_location = mysqli_real_escape_string($conn, $_POST['event_location']);
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);

    $email = $_SESSION['email'];
    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (!$user_query || mysqli_num_rows($user_query) === 0) {
        die("User not found.");
    }

    $user = mysqli_fetch_assoc($user_query);

    $institution_query = mysqli_query($conn, "SELECT * FROM public_institutions WHERE user_id = '{$user['user_id']}'");

    if (!$institution_query || mysqli_num_rows($institution_query) === 0) {
        die("Institution not found.");
    }

    $institution = mysqli_fetch_assoc($institution_query);
    $institution_id = $institution['institution_id'];

    $cover_path = '';
    if (!empty($_FILES['event_cover']['name'])) {
        $upload_dir = '../uploads/event_covers/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = time() . '_' . basename($_FILES['event_cover']['name']);
        $file_tmp = $_FILES['event_cover']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_ext)) {
            die("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $cover_path = 'uploads/event_covers/' . $file_name;
        } else {
            die("Failed to upload cover image.");
        }
    }

    $query = "INSERT INTO events (institution_id, title, description, location, date, time, event_type, cover_image, created_at)
              VALUES ('$institution_id', '$event_title', '$event_description', '$event_location', '$event_date', '$event_time', '$event_type', '$cover_path', NOW())";

    if (mysqli_query($conn, $query)) {
        header("Location: ../public_institution_profile.php");
        exit();
    } else {
        die("Database error: " . mysqli_error($conn));
    }
} else {
    die("Invalid request.");
}

<?php
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = mysqli_real_escape_string($conn, $_POST['event_id']);
    $title = mysqli_real_escape_string($conn, $_POST['event_title']);
    $description = mysqli_real_escape_string($conn, $_POST['event_description']);
    $date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $location = mysqli_real_escape_string($conn, $_POST['event_location']);
    $type = mysqli_real_escape_string($conn, $_POST['event_type']);

    $cover_sql = '';
    if (!empty($_FILES['event_cover']['name'])) {
        $upload_dir = "uploads/event_covers/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['event_cover']['name']);
        $file_tmp = $_FILES['event_cover']['tmp_name'];
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $cover_sql = ", cover_image = '$target_path'";
        } else {
            die("Failed to upload new cover image.");
        }
    }

    $update_query = "UPDATE events SET 
                        title = '$title', 
                        description = '$description', 
                        date = '$date', 
                        time = '$time', 
                        location = '$location', 
                        event_type = '$type' 
                        $cover_sql
                     WHERE event_id = '$event_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: ../public_institution_profile.php");
        exit();
    } else {
        die("Update failed: " . mysqli_error($conn));
    }
} else {
    die("Invalid request.");
}
?>

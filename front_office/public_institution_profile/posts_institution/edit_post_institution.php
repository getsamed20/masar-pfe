<?php
session_start();
include('../../includes/db.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$email = $_SESSION['email'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];

$result2 = mysqli_query($conn, "SELECT * FROM public_institutions WHERE user_id = $user_id");
$institution = mysqli_fetch_assoc($result2);
$institution_id = $institution['institution_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $new_content = mysqli_real_escape_string($conn, $_POST['content']);

    $check = mysqli_query($conn, "SELECT * FROM posts_institution WHERE post_id = $post_id AND institution_id = $institution_id");
    if (mysqli_num_rows($check) === 0) {
        die("Unauthorized access.");
    }

    mysqli_query($conn, "UPDATE posts_institution SET content = '$new_content' WHERE post_id = $post_id");

    if (!empty($_FILES['media']['name'][0])) {
        $media_q = mysqli_query($conn, "SELECT file_path FROM media WHERE post_institution_id = $post_id");
        while ($media = mysqli_fetch_assoc($media_q)) {
            $file_path = "../../uploads/" . $media['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        mysqli_query($conn, "DELETE FROM media WHERE post_institution_id = $post_id");

        $upload_dir = '../../uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['media']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['media']['error'][$key] === 0) {
                $original_name = basename($_FILES['media']['name'][$key]);
                $file_tmp = $_FILES['media']['tmp_name'][$key];
                $file_type = explode('/', $_FILES['media']['type'][$key])[0];

                $new_name = uniqid() . '_' . $original_name;
                $target_path = $upload_dir . $new_name;

                if (move_uploaded_file($file_tmp, $target_path)) {
                    mysqli_query($conn, "INSERT INTO media (post_institution_id, media_type, file_path, created_at) 
                        VALUES ('$post_id', '$file_type', '$new_name', NOW())");
                }
            }
        }
    }

    header("Location: ../public_institution_profile.php");
    exit;
}
?>

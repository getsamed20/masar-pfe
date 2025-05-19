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

$result2 = mysqli_query($conn, "SELECT * FROM startups WHERE user_id = $user_id");
$startup = mysqli_fetch_assoc($result2);
$startup_id = $startup['startup_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $check = mysqli_query($conn, "SELECT * FROM posts WHERE post_id = '$post_id' AND startup_id = '$startup_id'");
    if (mysqli_num_rows($check) === 0) {
        die("Unauthorized access.");
    }

    mysqli_query($conn, "UPDATE posts SET content = '$content' WHERE post_id = '$post_id'");

    foreach ($_FILES['media']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['media']['error'][$key] === 0) {
            $file_name = basename($_FILES['media']['name'][$key]);
            $file_tmp = $_FILES['media']['tmp_name'][$key];
            $file_type = explode('/', $_FILES['media']['type'][$key])[0];
            $new_name = uniqid() . '_' . $file_name;

            move_uploaded_file($file_tmp, "../../uploads/$new_name");

            mysqli_query($conn, "INSERT INTO media (post_id, media_type, file_path, created_at) 
                VALUES ('$post_id', '$file_type', '$new_name', NOW())");
        }
    }

    header("Location: ../startup_profile.php");
    exit();
}
?>

<?php
include('../includes/db.php');

if (!isset($_GET['email']) || !isset($_GET['role'])) {
    die("Invalid access.");
}

$email = $_GET['email'];
$role = $_GET['role'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
if (!$query || mysqli_num_rows($query) === 0) {
    die("User not found.");
}
$user = mysqli_fetch_assoc($query);
$user_id = $user['user_id'];

$table = $role === 'startup' ? 'startups' : 'public_institutions';

$check = mysqli_query($conn, "SELECT * FROM $table WHERE user_id = '$user_id'");
if (mysqli_num_rows($check) > 0) {
    header("Location: ../login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_email = $_POST['contact_email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $website = $_POST['website'];
    $facebook = $_POST['facebook'];
    $linkedin = $_POST['linkedin'];
    $x = $_POST['x'];
    $instagram = $_POST['instagram'];
    $about = $_POST['about'];
    $logo_name = null;

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $logo_name = uniqid() . '_' . basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'], "../uploads/$logo_name");
    }

    $insert = "INSERT INTO $table (user_id, contact_email, phone_number, address, website_url, facebook_link, linkedin_link, x_link, instagram_link, about_section, logo)
               VALUES ('$user_id', '$contact_email', '$phone', '$address', '$website', '$facebook', '$linkedin', '$x', '$instagram', '$about', '$logo_name')";

    if (mysqli_query($conn, $insert)) {
        header("Location: login.php");
        exit();
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complete Your Profile - Masar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Complete Your Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Contact Email</label>
            <input type="email" name="contact_email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Website URL</label>
            <input type="text" name="website" class="form-control">
        </div>
        <div class="mb-3">
            <label>Facebook</label>
            <input type="text" name="facebook" class="form-control">
        </div>
        <div class="mb-3">
            <label>LinkedIn</label>
            <input type="text" name="linkedin" class="form-control">
        </div>
        <div class="mb-3">
            <label>X (Twitter)</label>
            <input type="text" name="x" class="form-control">
        </div>
        <div class="mb-3">
            <label>Instagram</label>
            <input type="text" name="instagram" class="form-control">
        </div>
        <div class="mb-3">
            <label>About Section</label>
            <textarea name="about" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label>Upload Logo</label>
            <input type="file" name="logo" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Submit Info</button>
    </form>
</div>
</body>
</html>

<?php
session_start();
include('./db.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

$role = $user['role'];
$user_id = $user['user_id'];
$table = $role === 'startup' ? 'startups' : 'public_institutions';

// Fetch profile data
$profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $table WHERE user_id = '$user_id'"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $contact_email = $_POST['contact_email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $website = $_POST['website'];
    $facebook = $_POST['facebook'];
    $linkedin = $_POST['linkedin'];
    $x = $_POST['x'];
    $instagram = $_POST['instagram'];
    $about = $_POST['about'];

    // Handle logo upload if startup
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $logo_name = uniqid() . '_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], "../uploads/$logo_name");

        // Update with logo
        $update = "UPDATE $table SET contact_email='$contact_email', phone_number='$phone', address='$address', website_url='$website',
            facebook_link='$facebook', linkedin_link='$linkedin', x_link='$x', instagram_link='$instagram',
            about_section='$about', logo='$logo_name' WHERE user_id='$user_id'";
    } else {
        // Update without logo
        $update = "UPDATE $table SET contact_email='$contact_email', phone_number='$phone', address='$address', website_url='$website',
            facebook_link='$facebook', linkedin_link='$linkedin', x_link='$x', instagram_link='$instagram',
            about_section='$about' WHERE user_id='$user_id'";
    }
$profileLink= ($role=='startup')? '../startup_profile/startup_profile.php' : '../public_institution_profile/public_institution_profile.php';
    if (mysqli_query($conn, $update)) {
        header("Location: $profileLink ");
        exit();
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile - Masar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Your Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Contact Email</label>
            <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($profile['contact_email'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($profile['phone_number']); ?>">
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control"><?php echo htmlspecialchars($profile['address']); ?></textarea>
        </div>
        <div class="mb-3">
            <label>Website URL</label>
            <input type="text" name="website" class="form-control" value="<?php echo htmlspecialchars($profile['website_url']); ?>">
        </div>
        <div class="mb-3">
            <label>Facebook</label>
            <input type="text" name="facebook" class="form-control" value="<?php echo htmlspecialchars($profile['facebook_link']); ?>">
        </div>
        <div class="mb-3">
            <label>LinkedIn</label>
            <input type="text" name="linkedin" class="form-control" value="<?php echo htmlspecialchars($profile['linkedin_link']); ?>">
        </div>
        <div class="mb-3">
            <label>X (Twitter)</label>
            <input type="text" name="x" class="form-control" value="<?php echo htmlspecialchars($profile['x_link']); ?>">
        </div>
        <div class="mb-3">
            <label>Instagram</label>
            <input type="text" name="instagram" class="form-control" value="<?php echo htmlspecialchars($profile['instagram_link']); ?>">
        </div>
        <div class="mb-3">
            <label>About Section</label>
            <textarea name="about" class="form-control" rows="5"><?php echo htmlspecialchars($profile['about_section']); ?></textarea>
        </div>

        <div class="mb-3">
    <label>Upload Logo</label>
    <input type="file" name="logo" class="form-control">
    <?php if (!empty($profile['logo'])): ?>
        <p class="mt-2">Current logo: <img src="../uploads/<?php echo htmlspecialchars($profile['logo']); ?>"class="rounded-circle border" style="width: 70px; height: 70px; object-fit: cover;"></p>
    <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

</body>
</html>

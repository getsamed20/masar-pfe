<?php
session_start();
include('../includes/db.php');

$the_username = 'Guest';
$role = 'guest';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        $role = $user['role'];

        if ($role == 'startup') {
            $startup_query = mysqli_query($conn, "SELECT startup_name FROM startups WHERE user_id = '{$user['user_id']}'");
            $startup = mysqli_fetch_assoc($startup_query);
            $the_username = $startup['startup_name'];
        } elseif ($role == 'institution') {
            $institution_query = mysqli_query($conn, "SELECT institution_name FROM public_institutions WHERE user_id = '{$user['user_id']}'");
            $institution = mysqli_fetch_assoc($institution_query);
            $the_username = $institution['institution_name'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masar Platform - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../components/navbar.php'); ?>

    <div class="container text-center mt-5">
        <h1>Welcome, <?php echo htmlspecialchars($the_username); ?>!</h1>
        <p>Your role is: <strong><?php echo ucfirst($role); ?></strong></p>
        <p>Enjoy your time on the Masar Platform. Choose an action from the menu above.</p>
    </div>

    <?php include('../components/footer.php'); ?>
</body>
</html>

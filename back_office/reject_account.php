<?php
session_start();
include 'db.php';
include 'send_mail.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$pending_id = intval($_GET['id']);

$getUser = "SELECT email, name, role FROM pending_accounts WHERE id = $pending_id";
$result = mysqli_query($conn, $getUser);

if ($row = mysqli_fetch_assoc($result)) {
    $email = $row['email'];
    $name = $row['name'];
    $role = $row['role'];

    sendAccountStatusEmail($email, $name, $role, $validated);

    $delete = "DELETE FROM pending_accounts WHERE id = $pending_id";
    mysqli_query($conn, $delete);
}

header("Location: manage_pending_accounts.php?rejected=1");
exit();
?>

<?php
session_start();
include '../../includes/db.php';

if (isset($_GET['challenge_id'])) {
    $challenge_id = $_GET['challenge_id'];

    $getChallenge = mysqli_query($conn, "SELECT attached_file FROM challenges WHERE challenge_id = '$challenge_id'");

    if ($getChallenge && mysqli_num_rows($getChallenge) > 0) {
        $challenge = mysqli_fetch_assoc($getChallenge);

        if (!empty($challenge['attached_file']) && file_exists($challenge['attached_file'])) {
            unlink($challenge['attached_file']); 
        }

        mysqli_query($conn, "DELETE FROM challenges WHERE challenge_id = '$challenge_id'");
    }
}

header('Location: ../public_institution_profile.php');
exit;
?>

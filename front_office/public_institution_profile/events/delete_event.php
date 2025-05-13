<?php
include '../../includes/db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $getEvent = mysqli_query($conn, "SELECT cover_image FROM events WHERE event_id = '$event_id'");
    $event = mysqli_fetch_assoc($getEvent);

    if ($event && !empty($event['cover_image']) && file_exists($event['cover_image'])) {
        unlink($event['cover_image']);
    }

    mysqli_query($conn, "DELETE FROM events WHERE event_id = '$event_id'");
}

header('Location: ../public_institution_profile.php');
exit;
?>

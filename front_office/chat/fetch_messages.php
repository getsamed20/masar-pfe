<?php
session_start();
include('../includes/db.php'); // Adjust path as needed

$currentUserId = $_SESSION['user_id'] ?? null;

if (!$currentUserId) {
    echo "Unauthorized access.";
    exit;
}

$selectedId = isset($_GET['id']) ? intval($_GET['id']) : null;
$selectedType = $_GET['type'] ?? null;

if (!$selectedId || !$selectedType) {
    echo "No chat selected.";
    exit;
}

// Fetch contact details (logo and name) for display in messages
if ($selectedType === 'startup') {
    $res = mysqli_query($conn, "SELECT logo FROM startups WHERE user_id = $selectedId");
    $contact_row = mysqli_fetch_assoc($res);
    $contact_logo = $contact_row ? $contact_row['logo'] : '';
} else {
    $res = mysqli_query($conn, "SELECT logo FROM public_institutions WHERE user_id = $selectedId");
    $contact_row = mysqli_fetch_assoc($res);
    $contact_logo = $contact_row ? $contact_row['logo'] : '';
}

// Mark messages as seen when fetched (since they are now being displayed)
$mark_seen_query = "UPDATE messages
                     SET seen = 1
                     WHERE sender_id = $selectedId AND receiver_id = $currentUserId AND seen = 0";
mysqli_query($conn, $mark_seen_query);


$messages_query = "SELECT * FROM messages
                   WHERE (sender_id = $currentUserId AND receiver_id = $selectedId)
                     OR (sender_id = $selectedId AND receiver_id = $currentUserId)
                   ORDER BY sent_at ASC";
$messages_result = mysqli_query($conn, $messages_query);

$lastMessage = null;
while ($msg = mysqli_fetch_assoc($messages_result)):
    $lastMessage = $msg;
    $isSent = $msg['sender_id'] == $currentUserId;
    ?>
    <div class="message <?= $isSent ? 'sent' : 'received' ?>">
        <?php if (!$isSent): ?>
            <img src="../uploads/<?= htmlspecialchars($contact_logo) ?>" alt="Contact Avatar" class="message-avatar">
        <?php endif; ?>

        <div>
            <div class="message-bubble">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                <?php
                $media_query = "SELECT * FROM media_chat WHERE message_id = " . $msg['message_id'];
                $media_result = mysqli_query($conn, $media_query);
                while ($media = mysqli_fetch_assoc($media_result)) {
                    $media_url = "../uploads/" . htmlspecialchars($media['file_path']);
                    if ($media['media_type'] == 'image') {
                        echo "<br><a href='#' data-bs-toggle='modal' data-bs-target='#imageModal' data-img='$media_url'>
                                    <img src='$media_url' class='message-media'>
                                  </a>";
                    } elseif ($media['media_type'] == 'video') {
                        echo "<br><video width='200' controls class='message-media'><source src='$media_url' type='video/mp4'></video>";
                    } elseif ($media['media_type'] == 'document') {
                        $filename = basename($media['file_path']);
                        echo "<br><a href='$media_url' target='_blank' class='document-link' style='color: " . ($isSent ? 'white' : '#0C1BA3') . "'>$filename</a>";
                    }
                }
                ?>
            </div>
            <div class="message-time">
                <?= date('M d, H:i', strtotime($msg['sent_at'])) ?>
            </div>
            <?php
                // Check if this is the last message sent by the current user and if it's been seen
                if ($isSent) {
                    $seen_status_query = "SELECT COUNT(*) as count FROM messages WHERE message_id = " . $msg['message_id'] . " AND seen = 1";
                    $seen_status_result = mysqli_query($conn, $seen_status_query);
                    $seen_status = mysqli_fetch_assoc($seen_status_result)['count'];
                    if ($seen_status > 0) {
                        echo "<div class='message-seen'>Seen</div>";
                    }
                }
            ?>
        </div>
    </div>
<?php endwhile; ?>
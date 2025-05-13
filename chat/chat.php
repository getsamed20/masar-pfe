<?php
session_start();
include('../includes/db.php');
include('../components/navbar.php');

$currentUserId = $_SESSION['user_id'] ?? null;

if (!$currentUserId) {
    die("You must be logged in to access this page.");
}

$selectedId = isset($_GET['id']) ? intval($_GET['id']) : null;
$selectedType = $_GET['type'] ?? null;

$contacts = [];

$contact_query = "
    SELECT 
        IF(sender_id = $currentUserId, receiver_id, sender_id) AS contact_id,
        MAX(sent_at) AS last_message_time
    FROM messages
    WHERE sender_id = $currentUserId OR receiver_id = $currentUserId
    GROUP BY contact_id
    ORDER BY last_message_time DESC
";

$contact_result = mysqli_query($conn, $contact_query);

while ($row = mysqli_fetch_assoc($contact_result)) {
    $other_id = $row['contact_id'];

    // Count unseen messages
    $unseen_query = "SELECT COUNT(*) as unseen_count FROM messages WHERE sender_id = $other_id AND receiver_id = $currentUserId AND seen = 0";
    $unseen_result = mysqli_query($conn, $unseen_query);
    $unseen = mysqli_fetch_assoc($unseen_result)['unseen_count'] ?? 0;

    // Try fetching from startups
    $res = mysqli_query($conn, "SELECT startup_name AS name, logo FROM startups WHERE user_id = $other_id");
    if ($res && mysqli_num_rows($res) > 0) {
        $startup = mysqli_fetch_assoc($res);
        $contacts[] = [
            'id' => $other_id,
            'type' => 'startup',
            'name' => $startup['name'],
            'logo' => $startup['logo'],
            'unseen' => $unseen
        ];
        continue;
    }

    // Try fetching from public institutions
    $res = mysqli_query($conn, "SELECT institution_name AS name, logo FROM public_institutions WHERE user_id = $other_id");
    if ($res && mysqli_num_rows($res) > 0) {
        $inst = mysqli_fetch_assoc($res);
        $contacts[] = [
            'id' => $other_id,
            'type' => 'institution',
            'name' => $inst['name'],
            'logo' => $inst['logo'],
            'unseen' => $unseen
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat - Masar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .chat-container {
            display: flex;
            height: 90vh;
            margin-top: 20px;
        }
        .contacts {
            width: 25%;
            border-right: 1px solid #ccc;
            overflow-y: auto;
            padding: 15px;
        }
        .contacts a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 10px;
            transition: background-color 0.2s;
            position: relative;
        }
        .contacts a:hover, .contacts a.active {
            background-color: #f0f0f0;
        }
        .contacts img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 50%;
        }
        .red-dot {
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            position: absolute;
            top: 8px;
            left: 50px;
        }
        .chat-box {
            width: 75%;
            padding: 20px;
        }
        #imageModal img {
            max-width: 100%;
            height: auto;
        }
        .seen-text {
            font-size: 0.8em;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container chat-container">
    <div class="contacts">
        <h5>Contacts</h5>
        <?php foreach ($contacts as $contact): ?>
            <a href="chat.php?id=<?= $contact['id'] ?>&type=<?= $contact['type'] ?>" class="<?= ($selectedId == $contact['id']) ? 'active' : '' ?>">
                <img src="../uploads/<?= htmlspecialchars($contact['logo']) ?>" alt="<?= htmlspecialchars($contact['name']) ?>">
                <span><?= htmlspecialchars($contact['name']) ?></span>
                <?php if ($contact['unseen'] > 0 && $selectedId != $contact['id']): ?>
                    <span class="red-dot"></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="chat-box">
        <?php if ($selectedId && $selectedType): ?>
            <?php
            if ($selectedType === 'startup') {
                $res = mysqli_query($conn, "SELECT startup_name FROM startups WHERE user_id = $selectedId");
                $contact_row = mysqli_fetch_assoc($res);
                $contact_name = $contact_row ? $contact_row['startup_name'] : 'Unknown Startup';
            } else {
                $res = mysqli_query($conn, "SELECT institution_name FROM public_institutions WHERE user_id = $selectedId");
                $contact_row = mysqli_fetch_assoc($res);
                $contact_name = $contact_row ? $contact_row['institution_name'] : 'Unknown Institution';
            }

            // Mark messages as seen
            $mark_seen_query = "UPDATE messages 
                                SET seen = 1 
                                WHERE sender_id = $selectedId AND receiver_id = $currentUserId AND seen = 0";
            mysqli_query($conn, $mark_seen_query);

            $messages_query = "SELECT * FROM messages 
                               WHERE (sender_id = $currentUserId AND receiver_id = $selectedId) 
                                  OR (sender_id = $selectedId AND receiver_id = $currentUserId) 
                               ORDER BY sent_at ASC";
            $messages_result = mysqli_query($conn, $messages_query);
            ?>
            <h5>Chat with <?= htmlspecialchars($contact_name) ?></h5>



<div id="scrollableDiv" class="border rounded p-3 mb-3" style="height: 600px; overflow-y: auto; background-color: #f9f9f9;">
    <?php 
    $lastMessage = null;
    while ($msg = mysqli_fetch_assoc($messages_result)): 
        $lastMessage = $msg; // Store the last message for later checking
        ?>
        <div class="mb-2 <?= ($msg['sender_id'] == $currentUserId) ? 'text-end' : 'text-start' ?>">
            <div class="d-inline-block px-3 py-2 rounded" style="background-color: <?= ($msg['sender_id'] == $currentUserId) ? '#d1e7dd' : '#e2e3e5' ?>;">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                <?php
                $media_query = "SELECT * FROM media_chat WHERE message_id = " . $msg['message_id'];
                $media_result = mysqli_query($conn, $media_query);
                while ($media = mysqli_fetch_assoc($media_result)) {
                    $media_url = "../uploads/" . htmlspecialchars($media['file_path']);
                    if ($media['media_type'] == 'image') {
                        echo "<br><a href='#' data-bs-toggle='modal' data-bs-target='#imageModal' data-img='$media_url'>
                                <img src='$media_url' style='max-width: 200px; max-height: 200px;' class='rounded border mt-2'>
                              </a>";
                    } elseif ($media['media_type'] == 'video') {
                        echo "<br><video width='200' controls><source src='$media_url' type='video/mp4'></video>";
                    } elseif ($media['media_type'] == 'document') {
                        echo "<br><a href='$media_url' target='_blank'>📎 Document</a>";
                    }
                }
                ?>
            </div>
    <div style="font-size: 0.8em; color: #777;">
    <?= date('M d, H:i', strtotime($msg['sent_at'])) ?>
    <?php
    if (
        $msg['sender_id'] == $currentUserId &&
        $msg['message_id'] == $lastMessage['message_id']
    ) {
        // Check if the last message sent by current user was seen
        $seen_check = mysqli_query($conn, "SELECT seen FROM messages 
            WHERE message_id = {$msg['message_id']} AND receiver_id = $selectedId AND sender_id = $currentUserId");
        $seen_value = mysqli_fetch_assoc($seen_check)['seen'] ?? 0;
        if ($seen_value == 1) {
            echo "<br><span class='seen-text'>Seen</span>";
        }
    }
    ?>
</div>

        </div>
    <?php endwhile; ?>

    <!-- Display "Seen" under the last message if it was seen -->
    <?php if ($lastMessage && $lastMessage['sender_id'] == $currentUserId): ?>
        <?php
        // Check if the message was seen by the receiver
        $seen_query = "SELECT seen FROM messages 
               WHERE message_id = " . $lastMessage['message_id'] . " 
                 AND receiver_id = $selectedId 
                 AND sender_id = $currentUserId";

        $seen_result = mysqli_query($conn, $seen_query);
        $seen_status = mysqli_fetch_assoc($seen_result)['seen'] ?? 0;
        ?>
        <?php if ($seen_status == 1): ?>
            <div class="text-center text-muted" style="font-size: 0.8em;">Seen</div>
        <?php endif; ?>
    <?php endif; ?>
</div>


            <form id="chatForm" action="send_message.php?id=<?= $selectedId ?>&type=<?= $selectedType ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="receiver_id" value="<?= $selectedId ?>">

                <div class="input-group mb-2">
                    <label class="btn btn-outline-secondary mb-0" for="fileInput">
                        <i class="bi bi-plus-lg"></i> Add Attachment
                    </label>
                    <input type="file" name="attachment" id="fileInput" class="d-none" accept="image/*,video/*,.pdf,.doc,.docx">
                    <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-send-fill"></i> Send
                    </button>
                </div>
            </form>
        <?php else: ?>
            <h5>Select a contact to start chatting</h5>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for image preview -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="" id="previewImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    function scrollToBottom() {
    const div = document.getElementById("scrollableDiv");
    div.scrollTop = div.scrollHeight;
  }

  // Scroll on page load
  window.onload = scrollToBottom;
    // Image preview in modal
    const imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        const imgUrl = trigger.getAttribute('data-img');
        const previewImg = document.getElementById('previewImage');
        previewImg.src = imgUrl;
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
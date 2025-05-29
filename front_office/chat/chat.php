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

    $unseen_query = "SELECT COUNT(*) as unseen_count FROM messages WHERE sender_id = $other_id AND receiver_id = $currentUserId AND seen = 0";
    $unseen_result = mysqli_query($conn, $unseen_query);
    $unseen = mysqli_fetch_assoc($unseen_result)['unseen_count'] ?? 0;

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
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
        .chat-container {
            display: flex;
            height: calc(100vh - 60px);
            width: 100%;
            position: relative;
        }
        .contacts-sidebar {
            width: 30%;
            background-color: #0C1BA3;
            color: white;
            padding: 20px;
            overflow-y: auto;
            z-index: 1;
            position: relative;
        }
        .back-arrow {
            width: 24px;
            height: 24px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .recent-messages {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 400;
            font-size: 24px;
            color: white;
            margin-bottom: 20px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            padding: 15px 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .contact-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .contact-item.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .contact-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .contact-info {
            flex: 1;
        }
        .contact-name {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 18px;
            margin: 0;
        }
        .contact-role {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 400;
            font-size: 14px;
            margin: 0;
            opacity: 0.8;
        }
        .unseen-badge {
            background-color: red;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-left: 10px;
        }
        .chat-area {
            flex: 1;
            background-color: #F2F6FF;
            border-top-left-radius: 40px;
            border-bottom-left-radius: 40px;
            display: flex;
            flex-direction: column;
            padding: 20px;
            margin-left: -30px;
            padding-left: 50px;
            position: relative;
            z-index: 2;
            box-shadow: -5px 0 10px rgba(0, 0, 0, 0.05);
        }
        .chat-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-left: 10px;
        }
        .chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .chat-name {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: black;
            margin: 0;
        }
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0 10px 0;
            margin-bottom: 20px;
            scrollbar-width: thin;
            scrollbar-color: #0C1BA3 #F2F6FF;
        }
        .messages-container::-webkit-scrollbar {
            width: 6px;
        }
        .messages-container::-webkit-scrollbar-track {
            background: #F2F6FF;
        }
        .messages-container::-webkit-scrollbar-thumb {
            background-color: #0C1BA3;
            border-radius: 3px;
        }
        .message {
            display: flex;
            margin-bottom: 15px;
            max-width: 90%;
        }
        .message.received {
            align-self: flex-start;
        }
        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
            margin-right: 15px;
        }
        .message-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 10px;
        }
        .message-bubble {
            padding: 12px 16px;
            border-radius: 18px;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 400;
            font-size: 16px;
            max-width: 100%;
            word-wrap: break-word;
        }
        .message.received .message-bubble {
            background-color: white;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0);
            color: #0C1BA3;
            margin-left: 10px;
        }
        .message.sent .message-bubble {
            background-color: #0C1BA3;
            color: white;
            margin-right: 10px;
        }
        .message-time {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 400;
            font-size: 10px;
            color: #333;
            margin-top: 5px;
        }
        .message.sent .message-time {
            text-align: right;
        }
        .message-seen {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 400;
            font-size: 10px;
            color: #333;
            margin-top: 2px;
            text-align: right;
        }
        .message-media {
            margin-top: 8px;
            max-width: 200px;
            border-radius: 8px;
        }
        .input-area {
            display: flex;
            flex-direction: column;
            border: 1px solid #0C1BA3;
            border-radius: 24px;
            padding: 8px 15px;
            background-color: white;
        }
        .input-row {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .message-input {
            flex: 1;
            border: none;
            outline: none;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 400;
            font-size: 12px;
            color: #333;
            padding: 8px 12px;
            min-height: 40px;
            max-height: 120px;
            resize: none;
            overflow-y: auto;
        }
        .message-input::placeholder {
            color: #333;
            opacity: 0.6;
        }
        .attachment-icon, .send-icon {
            width: 40px;
            height: 40px;
            cursor: pointer;
        }
        .attachment-icon {
            margin-right: 10px;
        }
        .send-icon {
            margin-left: 10px;
        }
        .file-preview {
            display: flex;
            align-items: center;
            padding: 8px;
            margin-top: 8px;
            background-color: #f0f0f0;
            border-radius: 8px;
            max-width: 100%;
        }
        .file-preview img, .file-preview video {
            max-width: 100px;
            max-height: 100px;
            border-radius: 4px;
        }
        .file-info {
            margin-left: 10px;
            flex: 1;
        }
        .file-name {
            font-size: 12px;
            margin-bottom: 4px;
            word-break: break-all;
        }
        .remove-file {
            color: red;
            cursor: pointer;
            font-size: 12px;
        }
        .contacts-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .contacts-sidebar::-webkit-scrollbar-track {
            background: #0C1BA3;
        }
        .contacts-sidebar::-webkit-scrollbar-thumb {
            background-color: white;
            border-radius: 3px;
        }
        .document-link {
            text-decoration: none;
            border-bottom: 1px solid;
            padding-bottom: 1px;
        }
    </style>
</head>
<body>
<div class="chat-container">
    <div class="contacts-sidebar">
        <img src="arrow-icon.png" alt="Back" class="back-arrow" onclick="history.back()">
        <div class="recent-messages">Recent Messages</div>
        
        <div style="position: relative;">
            <?php foreach ($contacts as $contact): ?>
                <div class="contact-item <?= ($selectedId == $contact['id']) ? 'active' : '' ?>" 
                     onclick="window.location.href='chat.php?id=<?= $contact['id'] ?>&type=<?= $contact['type'] ?>'">
                    <img src="../uploads/<?= htmlspecialchars($contact['logo']) ?>" alt="<?= htmlspecialchars($contact['name']) ?>" class="contact-avatar">
                    <div class="contact-info">
                        <div class="contact-name"><?= htmlspecialchars($contact['name']) ?></div>
                        <div class="contact-role"><?= $contact['type'] == 'startup' ? 'Startup' : 'Public Institution' ?></div>
                    </div>
                    <?php if ($contact['unseen'] > 0 && $selectedId != $contact['id']): ?>
                        <div class="unseen-badge"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="chat-area">
        <?php if ($selectedId && $selectedType): ?>
            <?php
            if ($selectedType === 'startup') {
                $res = mysqli_query($conn, "SELECT startup_name, logo FROM startups WHERE user_id = $selectedId");
                $contact_row = mysqli_fetch_assoc($res);
                $contact_name = $contact_row ? $contact_row['startup_name'] : 'Unknown Startup';
                $contact_logo = $contact_row ? $contact_row['logo'] : '';
            } else {
                $res = mysqli_query($conn, "SELECT institution_name, logo FROM public_institutions WHERE user_id = $selectedId");
                $contact_row = mysqli_fetch_assoc($res);
                $contact_name = $contact_row ? $contact_row['institution_name'] : 'Unknown Institution';
                $contact_logo = $contact_row ? $contact_row['logo'] : '';
            }

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
            
            <div class="chat-header">
                <img src="../uploads/<?= htmlspecialchars($contact_logo) ?>" alt="<?= htmlspecialchars($contact_name) ?>" class="chat-avatar">
                <div class="chat-name"><?= htmlspecialchars($contact_name) ?></div>
            </div>
            
            <div class="messages-container" id="messagesContainer">
                <?php 
                $lastMessage = null;
                while ($msg = mysqli_fetch_assoc($messages_result)): 
                    $lastMessage = $msg;
                    $isSent = $msg['sender_id'] == $currentUserId;
                    ?>
                    <div class="message <?= $isSent ? 'sent' : 'received' ?>">
                        <?php if (!$isSent): ?>
                            <img src="../uploads/<?= htmlspecialchars($contact_logo) ?>" alt="<?= htmlspecialchars($contact_name) ?>" class="message-avatar">
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
                                $lastSeenQuery = mysqli_query($conn, "
                                    SELECT message_id FROM messages 
                                    WHERE sender_id = $currentUserId AND receiver_id = $selectedId AND seen = 1 
                                    ORDER BY message_id DESC LIMIT 1
                                ");
                                $lastSeenMessage = mysqli_fetch_assoc($lastSeenQuery);
                                $lastSeenMessageId = $lastSeenMessage['message_id'] ?? null;
                            ?>

                            <?php if ($isSent && $msg['message_id'] == $lastSeenMessageId): ?>
                                <div class='message-seen'>Seen</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <form id="chatForm" action="send_message.php?id=<?= $selectedId ?>&type=<?= $selectedType ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="receiver_id" value="<?= $selectedId ?>">
                
                <div class="input-area">
                    <div id="filePreviewContainer"></div>
                    <div class="input-row">
                        <label for="fileInput">
                            <img src="attachment-icon.png" alt="Add attachment" class="attachment-icon">
                        </label>
                        <input type="file" name="attachment" id="fileInput" class="d-none" accept="image/*,video/*,.pdf,.doc,.docx">
                        <textarea name="message" class="message-input" placeholder="Start typing..." rows="1"></textarea>
                        <button type="submit" style="background: none; border: none; padding: 0;">
                            <img src="send-icon.png" alt="Send" class="send-icon">
                        </button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                <h5 style="color: #666;">Select a contact to start chatting</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

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
        const container = document.getElementById("messagesContainer");
        container.scrollTop = container.scrollHeight;
    }

    window.onload = scrollToBottom;
    
    const imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        const imgUrl = trigger.getAttribute('data-img');
        const previewImg = document.getElementById('previewImage');
        previewImg.src = imgUrl;
    });
    
    const fileInput = document.getElementById('fileInput');
    const filePreviewContainer = document.getElementById('filePreviewContainer');
    const chatForm = document.getElementById('chatForm');
    
    fileInput.addEventListener('change', function(e) {
        filePreviewContainer.innerHTML = '';
        
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const fileType = file.type.split('/')[0];
            const filePreview = document.createElement('div');
            filePreview.className = 'file-preview';
            
            if (fileType === 'image') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    filePreview.appendChild(img);
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'file-info';
                    fileInfo.innerHTML = `
                        <div class="file-name">${file.name}</div>
                        <div class="remove-file" onclick="removeFile()">Remove</div>
                    `;
                    filePreview.appendChild(fileInfo);
                }
                reader.readAsDataURL(file);
            } 
            else if (fileType === 'video') {
                const video = document.createElement('video');
                video.controls = true;
                video.innerHTML = `<source src="${URL.createObjectURL(file)}" type="${file.type}">`;
                filePreview.appendChild(video);
                
                const fileInfo = document.createElement('div');
                fileInfo.className = 'file-info';
                fileInfo.innerHTML = `
                    <div class="file-name">${file.name}</div>
                    <div class="remove-file" onclick="removeFile()">Remove</div>
                `;
                filePreview.appendChild(fileInfo);
            } 
            else {
                filePreview.innerHTML = `
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="remove-file" onclick="removeFile()">Remove</div>
                    </div>
                `;
            }
            
            filePreviewContainer.appendChild(filePreview);
        }
    });
    
    function removeFile() {
        fileInput.value = '';
        filePreviewContainer.innerHTML = '';
    }
    
    const textarea = document.querySelector('.message-input');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        const maxHeight = 120;
        this.style.height = Math.min(this.scrollHeight, maxHeight) + 'px';
        this.style.overflowY = this.scrollHeight > maxHeight ? 'auto' : 'hidden';
    });
    
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.submit();
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
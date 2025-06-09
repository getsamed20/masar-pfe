<?php 
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Story</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'IBM Plex Sans Devanagari', sans-serif;
      background-color: #f8f9fa;
    }

    .main-content {
      margin-left: 250px;
      padding: 40px;
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .page-title {
      color: #0C1BA3;
      font-weight: 700;
      font-size: 20px;
      margin-bottom: 30px;
    }

    .input-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
      padding: 20px;
      margin-bottom: 20px;
    }

    .form-control {
      border: none;
      padding: 0;
      font-family: 'IBM Plex Sans Devanagari', sans-serif;
      width: 100%;
      outline: none;
      font-size: 16px;
    }

    .form-control::placeholder {
      color: rgba(12, 27, 163, 0.5);
      font-weight: 600;
    }

    #synopsis {
      height: 80px;
      resize: none;
    }

    #content {
      height: 300px;
      resize: vertical;
    }

    .media-section {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 20px;
    }

    .media-button {
      background-color: #0C1BA3;
      color: #F2F6FF;
      border-radius: 9px;
      font-weight: 600;
      font-size: 15px;
      padding: 10px 20px;
      border: none;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      display: inline-flex;
      align-items: center;
      cursor: pointer;
    }

    .media-button img {
      margin-right: 10px;
      width: 18px;
    }

    .media-preview {
      position: relative;
      width: 120px;
      height: 120px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .media-preview img, .media-preview video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .remove-media {
      position: absolute;
      top: 5px;
      right: 5px;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      border: none;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .publish-button {
      background-color: #0C1BA3;
      color: #F2F6FF;
      border-radius: 9px;
      font-weight: 600;
      font-size: 15px;
      padding: 12px 30px;
      border: none;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      margin-top: 40px;
      float: right;
      clear: both;
    }

    .hidden-file-input {
      display: none;
    }

    .word-counter {
      font-size: 14px;
      color: #666;
      margin-top: 8px;
    }
  </style>
</head>
<body>
  <?php include('admin_navbar.php'); ?>

  <div class="main-content">
    <div class="container">
      <h1 class="page-title">Add New Story</h1>

      <form action="submit_success_story.php" method="POST" enctype="multipart/form-data" id="mainForm">
        <!-- Title -->
        <div class="input-card">
          <input type="text" name="title" class="form-control" placeholder="Story Title*" required>
        </div>

        <!-- Synopsis with word limit and counter -->
        <div class="input-card">
          <textarea id="synopsis" name="synopsis" class="form-control" placeholder="Synopsis*" required></textarea>
          <div class="word-counter">
            <span id="synopsis-word-count">0</span>/50 words
          </div>
        </div>

        <!-- Description with increased height -->
        <div class="input-card">
          <textarea id="content" name="content" class="form-control" placeholder="Description*" required></textarea>
        </div>

        <!-- Media Upload -->
        <div class="media-section" id="mediaContainer">
          <button type="button" class="media-button" onclick="document.getElementById('media_files').click()">
            <img src="images/media-icon.png" alt="Media icon">
            Add Media
          </button>
          <input type="file" id="media_files" multiple accept="image/*,video/*" class="hidden-file-input">
        </div>

        <!-- Dynamic File Clones -->
        <div id="fileInputsContainer"></div>

        <button type="submit" class="publish-button">Publish</button>
      </form>
    </div>
  </div>

  <script>
    // Media Upload Logic
    document.addEventListener('DOMContentLoaded', function() {
      const mediaInput = document.getElementById('media_files');
      const mediaContainer = document.getElementById('mediaContainer');
      const fileInputsContainer = document.getElementById('fileInputsContainer');

      mediaInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);

        files.forEach(file => {
          const reader = new FileReader();
          reader.onload = function(event) {
            createMediaPreview(event.target.result, file);
          };
          reader.readAsDataURL(file);
        });

        mediaInput.value = '';
      });

      function createMediaPreview(src, file) {
        const previewDiv = document.createElement('div');
        previewDiv.className = 'media-preview';

        const media = file.type.startsWith('video/')
          ? document.createElement('video')
          : document.createElement('img');

        media.src = src;
        if (media.tagName === 'VIDEO') media.controls = true;

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-media';
        removeBtn.innerHTML = '×';

        const inputId = 'file_' + Math.random().toString(36).substring(2);
        const clonedInput = document.createElement('input');
        clonedInput.type = 'file';
        clonedInput.name = 'media_files[]';
        clonedInput.classList.add('hidden-file-input');
        clonedInput.dataset.id = inputId;

        const dt = new DataTransfer();
        dt.items.add(file);
        clonedInput.files = dt.files;

        removeBtn.addEventListener('click', function() {
          previewDiv.remove();
          const matchingInput = document.querySelector(`input[data-id="${inputId}"]`);
          if (matchingInput) matchingInput.remove();
        });

        previewDiv.appendChild(media);
        previewDiv.appendChild(removeBtn);
        mediaContainer.appendChild(previewDiv);
        fileInputsContainer.appendChild(clonedInput);
      }

      // Word count logic for synopsis
      const synopsisField = document.getElementById('synopsis');
      const wordCountDisplay = document.getElementById('synopsis-word-count');

      synopsisField.addEventListener('input', () => {
        const words = synopsisField.value.trim().split(/\s+/).filter(word => word.length > 0);
        const wordCount = words.length;

        if (wordCount > 50) {
          synopsisField.value = words.slice(0, 50).join(" ");
        }

        wordCountDisplay.textContent = Math.min(wordCount, 50);
      });
    });
  </script>
</body>
</html>

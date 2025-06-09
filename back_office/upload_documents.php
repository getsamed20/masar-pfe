<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document_file'])) {
    $admin_id = mysqli_real_escape_string($conn, $_SESSION['admin_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Get category

    $file = $_FILES['document_file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    $upload_dir = 'docs_uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . uniqid() . '.' . $file_ext;

    if (move_uploaded_file($file_tmp, $file_path)) {
        // Corrected SQL to include 'category' column
        $sql = "INSERT INTO documents (admin_id, title, description, category, file_path)
                VALUES ('$admin_id', '$title', '$description', '$category', '$file_path')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Document uploaded successfully!";
        } else {
            $_SESSION['message'] = "Database error: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['message'] = "Failed to upload the file.";
    }

    // Redirect to prevent form resubmission on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Check for and display messages from previous redirects
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            background-color: #F2F6FF;

        }
        .title-text {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700; 
            font-size: 20px;
            color: #0C1BA3;
        }
        .form-container {
            background-color: #F2F6FF;
            border-radius: 10px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3); /* w=0 y=4 blur=4 color black opacity 30% */
            padding: 30px;
            margin-top: 20px;
        }
        .input-rectangle {
            background-color: #FFFFFF;
            border-radius: 5px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4); /* w=0 y=4 blur=4 color black opacity 40% */
            border: none;
            padding: 10px 15px;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 600; /* Semibold */
            font-size: 16px;
            color: #0C1BA3;
            width: 100%;
        }
        .input-rectangle::placeholder {
            color: #0C1BA3;
            opacity: 0.7;
        }
        .description-rectangle {
            min-height: 100px; /* Wider in height */
            resize: vertical;
        }
        /* Keep btn-custom for the dropdown, but remove its file input specific styles */
        .btn-custom {
            background-color: #FFFFFF;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* y=2 blur=4 color black opacity 30% */
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 600; /* Semibold */
            font-size: 15px;
            color: #0C1BA3;
            border: none;
            padding: 8px 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            /* No cursor here, let the elements handle it */
        }
        .btn-custom .dropdown-toggle::after {
            color: #0C1BA3;
        }
        .btn-upload {
            margin-left:790px;
            background-color: #0C1BA3;
            color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 600; /* Semibold */
            font-size: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            border: none;
            padding: 8px 20px;
            cursor: pointer; /* Added pointer cursor for consistency */
        }
        .recent-uploads-title {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700; /* Regular */
            font-size: 20px;
            color: #0C1BA3;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        .document-card {
            background-color: #FFFFFF;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* y=2 blur=4 color black opacity 30% */
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .document-card-title {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 600; /* Semibold */
            font-size: 16px;
            color: #0C1BA3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
            margin-right: 15px;
        }
        .document-card-buttons .btn {
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 700; /* Bold */
            font-size: 10px;
            border-radius: 3px;
            padding: 5px 10px;
            margin-left: 10px;
            cursor: pointer; /* Added pointer cursor */
        }
        .btn-download {
            background-color: #0C1BA3;
            color: #EFEFFF;
            border: none;
        }
        .btn-delete {
            background-color: #B70600;
            color: #EFEFFF;
            border: none;
        }

        /* --- START of revised file input styles --- */
        .file-input-wrapper {
            position: relative; /* Crucial for positioning the child input */
            display: inline-block; /* Allows it to sit next to other inline elements */
            overflow: hidden; /* Hides parts of the large input if it overflows */
            margin-right: 10px;
            /* Mimic the appearance of .btn-custom for the wrapper */
            background-color: #FFFFFF;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Apply shadow to the wrapper */
            padding: 8px 15px; /* Match the padding of your other buttons */
            cursor: pointer; /* The whole wrapper should have a pointer cursor */
        }

        .file-input-wrapper input[type=file] {
            position: absolute; /* Position the actual input element absolutely */
            left: 0;
            top: 0;
            width: 100%; /* Make it cover the entire wrapper */
            height: 100%; /* Make it cover the entire wrapper */
            opacity: 0; /* Make it completely invisible */
            cursor: pointer; /* **This is where the pointer cursor comes from for the hidden input** */
            z-index: 2; /* Ensure it's on top of the visible content */
        }

        .file-input-wrapper .file-input-content {
            position: relative; /* Keep this content below the invisible input */
            z-index: 1;
            display: flex; /* Use flex to align icon and text */
            align-items: center; /* Vertically center icon and text */
            justify-content: center;
            color: #0C1BA3; /* Apply text color here */
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-weight: 600;
            font-size: 15px;
        }

        .file-input-wrapper .file-input-content img {
            margin-right: 8px; /* Space between icon and text */
        }
        /* --- END of revised file input styles --- */

        .icon-small {
            width: 16px; /* Adjust size as needed for the icon */
            height: 16px;
        }
    </style>
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="title-text">Upload a new document</h2>

    <?php if ($message): ?>
        <div class="alert alert-info mt-3"><?= $message ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="text" name="title" class="input-rectangle" placeholder="Title*" required>
            </div>
            <div class="mb-3">
                <textarea name="description" class="input-rectangle description-rectangle" placeholder="Description*"></textarea>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="dropdown me-3">
                    <button class="btn-custom dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Category*
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <li><a class="dropdown-item" href="#" data-value="Statistics & Reports">Statistics & Reports</a></li>
                        <li><a class="dropdown-item" href="#" data-value="Laws & Regulations">Laws & Regulations</a></li>
                        <li><a class="dropdown-item" href="#" data-value="Innovation & Technology">Innovation & Technology</a></li>
                        <li><a class="dropdown-item" href="#" data-value="Case Studies & Projects">Case Studies & Projects</a></li>
                        <li><a class="dropdown-item" href="#" data-value="Research & Publications">Research & Publications</a></li>
                        <li><a class="dropdown-item" href="#" data-value="Guides & Toolkits">Guides & Toolkits</a></li>
                    </ul>
                    <input type="hidden" name="category" id="selectedCategory" value="Statistics & Reports">
                </div>

                <div class="file-input-wrapper me-3">
                    <input type="file" name="document_file" id="documentFileInput" required>
                    <div class="file-input-content">
                        <img src="images/upload-icon-active.png" alt="Select File" class="icon-small">
                        <span id="fileNameDisplay">Select File*</span>
                    </div>
                </div>

                <button type="submit" class="btn-upload">Upload Document</button>
            </div>
        </form>
    </div>

    <h2 class="recent-uploads-title">Recent Uploads</h2>
    <?php
    $query = "SELECT * FROM documents ORDER BY uploaded_at DESC";
    $result = mysqli_query($conn, $query);
    while ($doc = mysqli_fetch_assoc($result)):
    ?>
        <div class="document-card">
            <span class="document-card-title">
                <?= htmlspecialchars($doc['title']) ?>.<?= pathinfo($doc['file_path'], PATHINFO_EXTENSION) ?>
            </span>
            <div class="document-card-buttons">
                <a href="<?= $doc['file_path'] ?>" target="_blank" class="btn btn-download">Download</a>
                <a href="delete_document.php?id=<?= $doc['document_id'] ?>"
                   class="btn btn-delete"
                   onclick="return confirm('Are you sure you want to delete this document?')">
                    Delete
                </a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('#categoryDropdown + ul .dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedValue = this.getAttribute('data-value');
            document.getElementById('selectedCategory').value = selectedValue;
            document.getElementById('categoryDropdown').textContent = this.textContent;
        });
    });

    document.getElementById('documentFileInput').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Select File*';
        document.getElementById('fileNameDisplay').textContent = fileName;
    });
</script>
</body>
</html>